<?php

namespace App\Repository;

use App\Models\Admin;
use App\Models\Provider;
use App\Models\Transaction;
use App\Models\TripRequest;
use App\Models\TripRequestDetail;
use App\Models\TripType;
use App\Models\User;
use App\Notifications\TripAccepted;
use App\Notifications\TripApprovedPendingPayment;
use App\Notifications\TripRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProviderRepository implements ProviderRepositoryInterface
{
    public function index()
    {
        $providers = User::where('role', 'provider')->get();

        return view('Pages.Providers.index', compact('providers'));
    }

    public function create()
    {
        return view('Pages.Providers.add');
    }

    public function store(Request $request)
    {
        try {

            $providers = new User();
            $providers->name = $request->name;
            $providers->email = $request->email;
            $providers->password = bcrypt($request->password);
            $providers->age = $request->age;
            $providers->national_id = $request->national_id;
            $providers->role = 'provider';
            $providers->code =$request->code ;
            $providers->save();
            toastr()->success(trans('تم اضافة مزود الخدمة بنجاح'));
            return redirect()->route('providers.index');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    public function edit($id)
    {

        $provider = User::findorfail($id);
        return view('pages.Providers.edit', compact('provider'));

    }

    public function update(array $data, User $provider)
    {
        // Validate incoming data
        // Validate incoming data
        $validatedData = validator($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $provider->id,
            'age' => 'nullable|integer|min:18',
            'national_id' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6', // Password is optional
            'code' => 'nullable|numeric', // لا تحتاج `required` مع `nullable`

        ])->validate();

        // If password is provided, hash and update it
        if (!empty($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        } else {
            unset($validatedData['password']); // Remove password if not provided
        }

        // Update agent data
        $provider->update($validatedData);

        return $provider;
    }

    public function destroy($request)
    {
        try {
            User::destroy($request->id);
            toastr()->error('تم حذف  مزود الخدمة بنجاح');
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function tripRequests()
    {
        $providerId = Auth::user()->id;
        $requests = TripRequestDetail::where('provider_id', $providerId)->where('status', 'pending')
        ->with('tripRequest','tripType','subTripType')
            ->get();

        return view('Pages.providers.requests', compact('requests'))->render();
    }

    public function approveRequestWaitingPayment(Request $request, $request_id)
    {
        try {
            DB::beginTransaction();

            $tripRequest = TripRequestDetail::findOrFail($request_id);
            $agentId = $tripRequest->tripRequest->agent_id;

            // تحديث حالة الحجز
            $currentDate = Carbon::parse($tripRequest->booking_datetime)->format('Y-m-d');
            $selectedTime = $request->booking_time;
            $newDateTime = Carbon::parse("{$currentDate} {$selectedTime}");

            $tripRequest->booking_datetime = $newDateTime;
            $tripRequest->status = 'waiting_payment';
            $tripRequest->save();

            // الحصول على آخر معاملة لكل عملة
            $lastTransactions = Transaction::where('agent_id', $agentId)
                ->orderBy('sequence', 'desc')
                ->get()
                ->groupBy('currency')
                ->map(function ($group) {
                    return $group->first();
                });

            // 1. معاملة الفاتورة (Invoice) لكل عملة
            foreach (['egp', 'usd', 'eur'] as $currency) {
                $lastBalance = $lastTransactions[$currency]->total_balance ?? 0;
                $newDebit = $tripRequest->{"total_price_{$currency}"} ?? 0;

                if ($newDebit > 0) {
                    Transaction::create([
                        'trip_request_detail_id' => $tripRequest->id,
                        'agent_id' => $agentId,
                        'credit' => 0,
                        'debit' => $newDebit,
                        'total_balance' => $lastBalance + $newDebit,
                        'credit_egp' => 0,
                        'debit_egp' => $currency == 'egp' ? $newDebit : 0,
                        'total_balance_egp' => $currency == 'egp' ? $lastBalance + $newDebit : ($lastTransactions['egp']->total_balance_egp ?? 0),
                        'debit_usd' => $currency == 'usd' ? $newDebit : 0,
                        'total_balance_usd' => $currency == 'usd' ? $lastBalance + $newDebit : ($lastTransactions['usd']->total_balance_usd ?? 0),
                        'credit_eur' => 0,
                        'debit_eur' => $currency == 'eur' ? $newDebit : 0,
                        'total_balance_eur' => $currency == 'eur' ? $lastBalance + $newDebit : ($lastTransactions['eur']->total_balance_eur ?? 0),
                        'sequence' => Transaction::getNextSequence(),
                        'created_at' => now(),
                        'type' => 'invoice',
                        'currency' => $currency
                    ]);
                }
            }

            // 2. معاملة الخصم (Discount) - خصم العملة المناسبة
            if ($tripRequest->discount > 0) {
                foreach (['egp', 'usd', 'eur'] as $currency) {
                    $discountAmount = $currency == 'egp' ? $tripRequest->discount :
                        ($currency == 'usd' ? $tripRequest->discount_usd : $tripRequest->discount_eur);

                    if ($discountAmount > 0) {
                        $lastBalance = $lastTransactions[$currency]->total_balance ?? 0;

                        Transaction::create([
                            'trip_request_detail_id' => $tripRequest->id,
                            'agent_id' => $agentId,
                            'credit' => $discountAmount,
                            'debit' => 0,
                            'total_balance' => $lastBalance - $discountAmount,
                            'credit_egp' => $currency == 'egp' ? $discountAmount : 0,
                            'debit_egp' => 0,
                            'total_balance_egp' => $currency == 'egp' ? $lastBalance - $discountAmount : ($lastTransactions['egp']->total_balance_egp ?? 0),
                            'credit_usd' => $currency == 'usd' ? $discountAmount : 0,
                            'debit_usd' => 0,
                            'total_balance_usd' => $currency == 'usd' ? $lastBalance - $discountAmount : ($lastTransactions['usd']->total_balance_usd ?? 0),
                            'credit_eur' => $currency == 'eur' ? $discountAmount : 0,
                            'debit_eur' => 0,
                            'total_balance_eur' => $currency == 'eur' ? $lastBalance - $discountAmount : ($lastTransactions['eur']->total_balance_eur ?? 0),
                            'sequence' => Transaction::getNextSequence(),
                            'created_at' => now(),
                            'type' => 'discount',
                            'currency' => $currency
                        ]);
                    }
                }
            }

            // 3. معاملة العمولة (Commission) - خصم العمولة مرة واحدة فقط
            $tripRequest->updateCommission($tripRequest);

            foreach (['egp', 'usd', 'eur'] as $currency) {
                $commissionAmount = $tripRequest->{"commission_value_{$currency}"} ?? 0;

                if ($commissionAmount > 0) {
                    $lastBalance = $lastTransactions[$currency]->total_balance ?? 0;

                    // تحقق من وجود معاملة عمولة سابقة لتجنب التكرار
                    $existingCommission = Transaction::where('agent_id', $agentId)
                        ->where('currency', $currency)
                        ->where('type', 'commission')
                        ->first();

                    if (!$existingCommission) {
                        Transaction::create([
                            'trip_request_detail_id' => $tripRequest->id,
                            'agent_id' => $agentId,
                            'credit' => $commissionAmount,
                            'debit' => 0,
                            'total_balance' => $lastBalance - $commissionAmount,
                            'credit_egp' => $currency == 'egp' ? $commissionAmount : 0,
                            'debit_egp' => 0,
                            'total_balance_egp' => $currency == 'egp' ? $lastBalance - $commissionAmount : ($lastTransactions['egp']->total_balance_egp ?? 0),
                            'credit_usd' => $currency == 'usd' ? $commissionAmount : 0,
                            'debit_usd' => 0,
                            'total_balance_usd' => $currency == 'usd' ? $lastBalance - $commissionAmount : ($lastTransactions['usd']->total_balance_usd ?? 0),
                            'credit_eur' => $currency == 'eur' ? $commissionAmount : 0,
                            'debit_eur' => 0,
                            'total_balance_eur' => $currency == 'eur' ? $lastBalance - $commissionAmount : ($lastTransactions['eur']->total_balance_eur ?? 0),
                            'sequence' => Transaction::getNextSequence(),
                            'created_at' => now(),
                            'type' => 'commission',
                            'currency' => $currency
                        ]);
                    }
                }
            }

            // إعادة حساب رصيد العميل بعد المعاملات
            Transaction::recalculateAgentBalance($agentId);

            DB::commit();
            toastr()->success('تم قبول الرحلة بنجاح وحساب العملات المختلفة!');
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    public function approveRequest($request_id)
    {

        try {


            $tripRequestDetail = TripRequestDetail::find($request_id);

            // تحديث حالة الرحلة إلى مؤكد
            $tripRequestDetail->status = 'confirmed';
            $tripRequestDetail->save();
            $tripRequestDetail->updateCommission($tripRequestDetail);


            toastr()->success('تم قبول الرحلة، وتأكيدها بنجاح.');
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    public function providerApprovedTrips()
    {
        $providerId = Auth::user()->id;
        $trips = TripRequestDetail::where('provider_id', $providerId)
            ->where('status', 'confirmed')
            ->get();

        return view('Pages.Providers.provider_approved', compact('trips'));
    }
    public function rejectRequest(Request $request, $request_id)
    {
        try {
     $tripRequest = TripRequestDetail::findOrFail($request_id);
    $tripRequest->status = 'canceled';
    $tripRequest->rejection_reason = $request->rejection_reason;
    $tripRequest->save();

            toastr()->error('تم رفض الرحلة بنجاح!');
            return redirect()->back();

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function TripsWatingConfirm()
    {
        $providerId = Auth::user()->id;
        $requests = TripRequest::where('provider_id', $providerId)
            ->whereHas('details', function ($query) {
                $query->where('status', 'waiting_confirmation'); // ✅ يجلب فقط الرحلات التي تنتظر التأكيد
            })
            ->with([
                'trip',
                'agent',
                'details' => function ($query) {
                    $query->where('status', 'waiting_confirmation'); // ✅ نفس الشرط داخل العلاقة
                }
            ])
            ->get();

        return view('Pages.providers.watingconfirm', compact('requests'));
    }

    public function confirmedTrips()
    {
        $providerId = Auth::user()->id;
        $trips = TripRequestDetail::where('provider_id', $providerId)
            ->where('status', 'confirmed')
            ->get();
        return view('Pages.Providers.confirmed_trips', compact('trips'));
    }

    public function rejectedTrips()
    {
        $providerId = Auth::user()->id;
        $trips = TripRequestDetail::where('provider_id', $providerId)
            ->where('status', 'canceled')
            ->get();
        return view('Pages.Providers.rejected_trips', compact('trips'));
    }
    public function showProfile($id)
    {
        $provider = Provider::findOrFail($id);

        // جلب جميع الرحلات التي عالجها البروفايدر
        $tripRequests = TripRequest::where('provider_id', $id) // تأكد من وجود هذا الحقل في جدول `trip_requests`
        ->with(['trip', 'detail'])
            ->get();

        return view('pages.Providers.profile', compact('provider', 'tripRequests'));
    }

}
