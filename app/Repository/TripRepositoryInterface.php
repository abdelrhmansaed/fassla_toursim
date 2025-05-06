<?php

namespace App\Repository;

use App\Http\Requests\AgentRequest;
use App\Models\Agent;
use App\Models\Provider;
use App\Models\Trip;
use Illuminate\Http\Request;
interface TripRepositoryInterface
{
    public function index();
    public function create();
    public function store(Request  $request );
    public function edit($id);
    public function update(Request $request, $id);
    public function destroy($id);

    public function getSubTripTypes($tripId);



}
