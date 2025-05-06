<?php

namespace App\Http\Controllers\providers;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Provider;
use App\Models\TripRequest;
use App\Models\User;
use App\Notifications\TripAccepted;
use App\Notifications\TripApproved;
use App\Notifications\TripApprovedPendingPayment;
use App\Notifications\TripRejected;
use App\Repository\ProviderRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProviderController extends Controller
{
    protected $provider;

    public function __construct(ProviderRepositoryInterface $provider)
    {
        $this->provider = $provider;
    }
    public function index()
    {
        return $this->provider->index();

    }
    public function create()
    {
        return $this->provider->create();
    }
    public function store(Request $request)
    {
        return $this->provider->store($request);
    }
    public function edit($id){
        return $this->provider->edit($id);
    }

    public function update(Request $request, User $provider)
    {
        try {
            // تحديث البيانات
            $updatedAgent = $this->provider->update($request->all(), $provider);

            toastr()->success('تم تعديل بيانات المندوب بنجاح');
            return redirect()->route('providers.index');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    public function destroy(Request $request){
        return $this->provider->destroy($request);
    }


    public function tripRequests()
    {
        return $this->provider->tripRequests();
    }

    public function approveRequestWaitingPayment(Request $request,$request_id)
    {
        return $this->provider->approveRequestWaitingPayment( $request,$request_id);
    }
    public function approveRequest($request_id)
    {
        return $this->provider->approveRequest($request_id);
    }


    public function rejectRequest(Request $request,$request_id)
    {
        return $this->provider->rejectRequest($request,$request_id);
    }


    public function TripsWatingConfirm()
    {
     return $this->provider->TripsWatingConfirm();
    }


    public function confirmedTrips()
    {
        return $this->provider->confirmedTrips();
    }

    public function rejectedTrips()
    {
        return $this->provider->rejectedTrips();
    }


    public function showProfile($id)
    {
        return $this->provider->showProfile($id);
    }
    public function providerApprovedTrips()
    {
        return $this->provider->providerApprovedTrips();
    }

}
