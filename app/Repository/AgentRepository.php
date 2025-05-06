<?php

namespace App\Repository;

use App\Http\Requests\AgentRequest;
use App\Models\Admin;
use App\Models\Agent;
use App\Models\Provider;
use App\Models\Transaction;
use App\Models\Trip;
use App\Models\TripRequest;
use App\Models\TripRequestDetail;
use App\Models\User;
use App\Notifications\TripRequested;
use App\Repository\AgentRepositoryInterface;
use App\Services\CurrencyConverter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AgentRepository implements AgentRepositoryInterface
{
    public function index()
    {
        $agents = User::where('role', 'agent')->get();
        return view('Pages.Agents.index', compact('agents'));
    }
    public function create()
    {
        return view('Pages.Agents.add') ;
    }
    public function store(AgentRequest $request )
    {
        try {

            $agent = new User();
            $agent ->name  =$request->name;
            $agent ->email  =$request->email;
            $agent ->password  = bcrypt($request->password);
            $agent ->age  =$request->age;
            $agent ->national_id  =$request->national_id;
            $agent->role = 'agent';
            $agent->code =$request->code ;
            $agent->save();
            toastr()->success(trans('تم اضافة المندوب بنجاح'));
            return redirect()->route('agents.index');

        }

        catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    public function edit($id){

        $agent = User::findorfail($id);
        return view('pages.Agents.edit',compact('agent'));

    }

    public function update(array $data, User $agent)
    {
        // Validate incoming data
        $validatedData = validator($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $agent->id,
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

        // Ensure the user being updated is actually an agent
        if ($agent->role !== 'agent') {
            abort(403, 'هذا المستخدم ليس مندوبًا.');
        }

        // Update agent data in users table
        $agent->update($validatedData);

        return $agent;
    }


    public function destroy($request)
    {
        try {
            User::destroy($request->id);
            toastr()->error('تم حذف المندوب بنجاح');
            return redirect()->back();
        }

        catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }




    public function storeTripRequest(Request $request)
    {
//        dd($request->all());

        DB::beginTransaction();
        try {
//
//            $validated = $request->validate([
//                'booking_number' => 'required|string',
//                'receipt_number' => 'required|string',
//                'hotel_name' => 'required|string',
//                'provider_id' => 'required|exists:users,id',
//                'adult_count' => 'required|array',
//                'adult_count.*' => 'integer|min:0',
//                'children_count' => 'required|array',
//                'children_count.*' => 'integer|min:0',
//                'total_people' => 'required|array',
//                'total_people.*' => 'integer|min:1',
//                'adult_price' => 'required|array',
//                'adult_price.*' => 'numeric|min:0',
//                'child_price' => 'required|array',
//                'child_price.*' => 'numeric|min:0',
//                'total_price' => 'required|array',
//                'total_price.*' => 'numeric|min:0',
//            ]);
        $tripIds = $request->input('trip_type_id', []);
        $providider_id = $request->input('provider_id', []);

        $sub_trip_type_ids = $request->input('sub_trip_type_id', []);
            // ✅ التحقق من صحة المصفوفة

            // ✅ تأكيد أن المستخدم مسجل الدخول كوكيل
            $agent = Auth::user();
            if (!$agent || $agent->role !== 'agent') {
                abort(403, 'Unauthorized action.');
            }
            $currency = $request->input('currency' , []);  // هنا بنحدد الـ default زي ما إنت حاطط في الـ HTML
            $discount_value = $request->input('discount_value', []);
//            // ✅ إنشاء الطلب الرئيسي في جدول trip_requests
            $tripRequest = TripRequest::create([
                'agent_id' => $agent->id,

                'booking_number' => $request->booking_number,
                'receipt_number' => $request->receipt_number,
                'hotel_name' => $request->hotel_name,
                'total_price_egp' => array_sum($request->price_egp),
                'total_price_usd' =>  array_sum($request->price_usd),
                'total_price_eur' =>  array_sum($request->price_eur),

                'total_price'=> array_sum($request->total_price),
                'payment_status'=>$request->payment_status,
            ]);
            Log::info("تم حفظ TripRequest بنجاح: ", ['trip_request_id' => $tripRequest->id]);

            // ✅ التأكد من أن هناك بيانات للرحلات
// احصل على الأسعار مرة واحدة
            try {
                $usdRate = CurrencyConverter::convertToEGP(1, 'USD');
                $eurRate = CurrencyConverter::convertToEGP(1, 'EUR');
            } catch (\Exception $e) {
                $usdRate = 0; // سعر احتياطي
                $eurRate = 0;
            }
        foreach ($tripIds as $index => $tripId) {
            $subTripId = $sub_trip_type_ids[$index] ?? null;




            // خلي أول عنصر sub_trip_type_id = null
            if ($index === 0) {
                $subTripId = null;
            }
            else {
                $subTripId = $sub_trip_type_ids[$index - 1] ?? null;
            }

            $priceEGP = $request->price_egp[$index] ?? 0.0;
            $priceUSD = $request->price_usd[$index] ?? 0.0;
            $priceEUR = $request->price_eur[$index] ?? 0.0;
            $totalConvertedPrice = $priceEGP + ($priceUSD * $usdRate) + ($priceEUR * $eurRate);
// إنشاء التفاصيل
            $tripDetail = TripRequestDetail::create([
                'trip_request_id'         => $tripRequest->id,
                'trip_type_id'            => $tripId,
                'sub_trip_type_id'        => $subTripId,
                'provider_id'             => $request->provider_id[$index] ?? null,
                'total_people'            => $request->total_people[$index] ?? 1,
                'adult_count'             => $request->adult_count[$index] ?? 0,
                'children_count'          => $request->children_count[$index] ?? 0,
                'adult_price'             => $request->adult_price[$index] ?? 0.0,
                'children_price'          => $request->child_price[$index] ?? 0.0,
                'total_price'             => $request->total_price[$index] ?? 0.0,
                'total_price_egp'         => $priceEGP,
                'total_price_usd'         => $priceUSD,
                'total_price_eur'         => $priceEUR,
                'converted_total_price_egp'=> $totalConvertedPrice,
                'discount'                 => $request->discount_value[$index] ?? 0,
                'currency'                 => $request->currency[$index] ?? null,
                'booking_datetime'        => $request->booking_datetime[$index] ?? null,
                'image'                   => null,
                'status'                  => 'pending',
            ]);

//                        // التحقق من أن هناك قيمة صحيحة قبل إدراجها في المعاملة



                }





            DB::commit(); // ✅ تأكيد الحفظ إذا لم تحدث أي أخطاء
            toastr()->success('تم ارسال الطلب بنجاح.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack(); // 🛑 إلغاء الحفظ عند حدوث خطأ
            Log::error("خطأ أثناء حفظ الطلب: " . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء الحفظ: ' . $e->getMessage());
        }
    }



    public function myRequests()
    {
        $agentId = Auth::user()->id;
        $requests = TripRequest::where('agent_id', $agentId)
            ->whereHas('details', function ($query) {
                $query->where('status', 'pending');
            })
            ->with(['agent', 'details']) // إضافة التفاصيل
            ->get();

        return view('Pages.Agents.requests', compact('requests'));
    }
    public function confirmedTrips()
    {
        $agentId = Auth::user()->id;
        $trips = TripRequest::where('agent_id', $agentId)
            ->with(['trip', 'agent', 'details' => function ($query) {
                $query->where('status', 'confirmed');
            }])
            ->whereHas('details', function ($query) {
                $query->where('status', 'confirmed');
            })
            ->get();
        return view('Pages.Agents.confirmed_trips', compact('trips'));
    }

    public function rejectedTrips()
    {
        $agentId = Auth::user()->id;
   $trips = TripRequest::where('agent_id', $agentId)
            ->with(['trip', 'agent', 'details' => function ($query) {
    $query->where('status', 'canceled');
              }])
            ->whereHas('details', function ($query) {
         $query->where('status', 'canceled');
              })
            ->get();
        return view('Pages.Agents.rejected_trips', compact('trips'));
    }

    public function requestTrip($trip_id)
    {
        try {
            $trip = Trip::findOrFail($trip_id);
            $agent = Auth::user();

            if (!$agent) {
                toastr()->error('حدث خطأ! تأكد من تسجيل الدخول.');
                return redirect()->back();
            }
            return view('Pages.Agents.trip_request_form', compact('trip'));
        }
        catch (\Exception $e) {
            Log::error('Error requesting trip: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    public function dashboard()
    {
        $agentId = Auth::user()?->id; // جلب معرف المندوب

        // جلب كل الرحلات المطلوبة حسب agent_id، ثم حساب عدد كل حالة من جدول trip_request_details
        $requestedTrips = TripRequest::where('agent_id', $agentId)
            ->whereHas('details', function ($query) {
                $query->where('status', 'pending');
            })->count();

        $acceptedTrips = TripRequest::where('agent_id', $agentId)
            ->whereHas('details', function ($query) {
                $query->where('status', 'confirmed');
            })->count();

        $rejectedTrips = TripRequest::where('agent_id', $agentId)
            ->whereHas('details', function ($query) {
                $query->where('status', 'canceled');
            })->count();

        $allTrips = Trip::count();

        return view('Dashboard.agent.index', compact('requestedTrips', 'acceptedTrips', 'rejectedTrips', 'allTrips'));
    }

    public function showProfile($id)
    {
        $agent = Agent::findOrFail($id);

        // جلب جميع الرحلات التي طلبها المندوب
        $tripRequests = TripRequestDetail::where('agent_id', $id)
            ->with(['trip', 'details'])
            ->get();

        return view('pages.Agents.profile', compact('agent', 'tripRequests'));
    }


    public function pay(Request $request, $tripRequestDetailId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
        ]);

        try {
            $transaction = Transaction::where('trip_request_detail_id', $tripRequestDetailId)->firstOrFail();

            $amountPaid = $request->amount - ($request->discount ?? 0);

            // زود المبلغ المدفوع
            $transaction->credit += $amountPaid;
            $transaction->save();

            // بعد التعديل.. نحدث كل الرصيد التراكمي
            Transaction::recalculateAgentBalance($transaction->agent_id);

            toastr()->success('تم دفع الطلب بنجاح.');
        } catch (\Exception $e) {
            toastr()->error('فشل الدفع: ' . $e->getMessage());
        }

        return redirect()->back();
    }

}



