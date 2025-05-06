<?php

namespace App\Http\Controllers\Agents;

use App\Http\Controllers\Controller;
use App\Http\Requests\AgentRequest;

use App\Models\Provider;
use App\Models\Trip;
use App\Models\TripRequest;
use App\Models\TripRequestDetail;
use App\Models\User;
use App\Notifications\TripRequested;
use App\Repository\AgentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AgentController extends Controller
{
    protected $agent;
    public function __construct(AgentRepositoryInterface $agent)
    {
        $this->agent = $agent;
    }
    public function index()
    {
      return $this->agent->index();

    }
    public function create()
    {
        return $this->agent->create();
    }
    public function store(  AgentRequest  $request)
    {
        return $this->agent->store( $request);
    }
    public function edit($id){
        return $this->agent->edit($id);
    }

    public function update(Request $request, User $agent)
    {
        try {
            // تحديث البيانات
            $updatedAgent = $this->agent->update($request->all(), $agent);

            toastr()->success('تم تعديل بيانات المندوب بنجاح');
            return redirect()->route('agents.index');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    public function destroy(Request $request){
        return $this->agent->destroy($request);
    }

    public function storeTripRequest(Request $request)
    {
        return $this->agent->storeTripRequest($request);
    }

    public function myRequests()
    {
   return $this->agent->myRequests();
    }

    public function confirmedTrips()
    {
      return $this->agent->confirmedTrips();
    }

    public function rejectedTrips()
    {
        return $this->agent->rejectedTrips();

    }


    public function requestTrip($trip_id)
    {
        return $this->agent->requestTrip($trip_id);
    }


//    public function confirmTrip($request_id)
//    {
//        $agentId = Auth::guard('agent')->user()->id;
//
//        TripRequest::where('id', $request_id)->update(['status' => 'confirmed']);
//        toastr()->success('تم تأكيد الحجز!');
//        return redirect()->back();
//    }
//
//    public function cancelTrip($request_id)
//    {
//        TripRequest::where('id', $request_id)->update(['status' => 'canceled']);
//        toastr()->error('تم إلغاء الحجز!');
//        return redirect()->back();
//    }

    public function dashboard()
    {
        return $this->agent->dashboard();
    }


    public function showProfile($id)
    {
        return $this->agent->showProfile($id);
    }

    public function pay(Request $request, $tripRequestDetailId){
        return $this->agent->pay($request, $tripRequestDetailId);
    }


}
