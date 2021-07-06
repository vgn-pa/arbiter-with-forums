<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\User;
use App\Planet;
use App\Galaxy;
use App\Alliance;
use Auth;
use App\FleetMovement;

class FleetMovementController extends ApiController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $movement = $request->input('movement');
        $type = $request->input('type');
        $id = $request->input('id');
        $limit = $request->input('limit', null);

        if($id == 0) $id = null;

        switch($type) {
            CASE 'planet': $planets = [$id];
                break;
            CASE 'galaxy': $planets = Planet::where('galaxy_id', $id)->pluck('id');
                break;
            CASE 'alliance': $planets = Planet::where('alliance_id', $id)->pluck('id');
                break;
        }

        $query = FleetMovement::with('planetTo', 'planetTo.alliance', 'planetFrom', 'planetFrom.alliance')->orderBy('tick', 'DESC')->orderBy('mission_type');

        if($movement == 'outgoing') $query->whereIn('planet_from_id', $planets);
        if($movement == 'incoming') $query->whereIn('planet_to_id', $planets);

        return $query->paginate($limit)->appends($request->input());
    }
}
