<?php

namespace App\Http\Controllers\Trips;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Provider;
use App\Models\trip;
use App\Models\TripRequest;
use App\Notifications\TripAccepted;
use App\Notifications\TripRequested;
use App\Repository\TripRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class TripController extends Controller
{
    protected $tirp;

    public function __construct(TripRepositoryInterface $tirp)
    {
        $this->trip = $tirp;
    }

    public function index()
    {
        return $this->trip->index();

    }

    public function create()
    {
        return $this->trip->create();
    }

    public function store(Request $request)
    {
        return $this->trip->store($request);
    }

    public function edit($id)
    {
        return $this->trip->edit($id);
    }

    public function update(Request $request, $id)
    {
        return $this->trip->update($request, $id);
    }


    public function destroy($id)
    {
        return $this->trip->destroy($id);
    }
//    public function showRequestForm(Trip $trip) {
//        return view('Pages.Trips.details', compact('trip'));
//    }
//    public function requestTrip($trip_id)
//    {
//        try {
//            $trip = Trip::findOrFail($trip_id);
//            $agent = auth('agent')->user();
//
//            if (!$agent) {
//                return redirect()->back()->withErrors(['error' => 'يجب تسجيل الدخول كمندوب']);
//            }
//
//            // التحقق من وجود طلب مسبق
//            $existingOrder = TripRequest::where('trip_id', $trip->id)->where('agent_id', $agent->id)->first();
//            if ($existingOrder) {
//                return redirect()->back()->with('info', 'تم طلب الرحلة مسبقاً');
//            }
//
//            // إنشاء الطلب
//            $trip = new TripRequest();
//            $trip->trip_id = $trip->id;
//            $trip->agent_id = $agent->id;
//            $trip->status = 'pending';
//            $trip->save();
//
//            return redirect()->route('Pages.Trips.details', $trip->id)->with('success', 'تم طلب الرحلة، الرجاء إدخال التفاصيل');
//        } catch (\Exception $e) {
//            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
//        }
//    }

    public function showBookings()
    {
        $tripRequests = TripRequest::with('trip', 'detail')->get();
        return view('Pages.Requests.bookings', compact('tripRequests'));
    }

    public function downloadPDF($id)
    {
        $trip = TripRequest::with(['trip', 'agent', 'detail'])->findOrFail($id);

        $pdf = Pdf::loadView('Pages.Trips.trip_pdf', compact('trip'))
            ->setPaper('a4', 'portrait') // ضبط حجم الورقة
            ->setOptions([
                'defaultFont' => 'amiri',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);

        return $pdf->download('trip_details.pdf');
    }

    public function getSubTripTypes($tripId){
        return $this->trip->getSubTripTypes($tripId);
    }



}
