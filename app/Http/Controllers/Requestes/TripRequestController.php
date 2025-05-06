<?php

namespace App\Http\Controllers\Requestes;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Trip;
use App\Models\TripRequest;
use App\Models\TripRequestDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TripRequestController extends Controller
{
    public function confirmedTrips()
    {
        $trips = TripRequestDetail::where('status', 'confirmed')
            ->get();
        return view('Pages.Requestes.confirmed_trips', compact('trips'));
    }

    public function rejectedTrips()
    {
        $trips = TripRequestDetail::where('status', 'canceled')
            ->get();
        return view('Pages.Requestes.rejected_trips', compact('trips'));
    }
    public function providerApprovedTrips()
    {
        $trips = TripRequestDetail::where('status', 'waiting_payment')
            ->with('tripRequest','tripType','subTripType')
            ->get();

        return view('Pages.Requestes.provider_approved', compact('trips'));
    }

    public function uploadPaymentProof(Request $request, $trip_id)
    {


        try {
            $request->validate([
                'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // البحث عن تفاصيل الرحلة
            $tripDetail = TripRequestDetail::findOrFail($trip_id);
            if (!$tripDetail) {
                return back()->withErrors(['error' => 'لم يتم العثور على تفاصيل الرحلة.']);
            }

            // حفظ الصورة داخل مجلد `storage/app/public/payment_proofs`
            $imagePath = $request->file('payment_proof')->store('payment_proofs', 'public');

            $tripDetail->image = $imagePath;
            if ($request->has('payment_note')) {
                $tripDetail->payment_note = $request->payment_note;
            }
            $tripDetail->status = 'confirmed'; // بانتظار موافقة البروفايدر
            $tripDetail->save();
               return redirect()->back();

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function showTripDetails($trip_id)
    {
        // جلب بيانات الرحلة مع العلاقات المطلوبة
        $trip = TripRequest::with(['details.tripRequest', 'details.transactions', 'agent'])
            ->findOrFail($trip_id);
        return view('Pages.Trips.details', compact('trip'));

    }

    public function tripRequests()
    {
        $requests = TripRequestDetail::where('status', 'pending')
            ->get();
        return view('Pages.Requestes.requests',compact('requests'));
    }

}
