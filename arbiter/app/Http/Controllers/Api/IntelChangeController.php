<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\IntelChange;
use App\FleetMovement;
use App\Planet;

class IntelChangeController extends ApiController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return IntelChange::with('planet', 'allianceFrom', 'allianceTo')->where('tick', '>', $this->tick-(7*24))->where('is_seen', 0)->orderBy('tick', 'DESC')->get();
    }

    public function store(Request $request)
    {
        $movement = FleetMovement::with('planetTo', 'planetTo.alliance', 'planetFrom', 'planetFrom.alliance')->find($request->input('id'));

        if($movement->planetTo->alliance) {
            $planet = Planet::find($movement->planetFrom->id);
            $currentAlly = $planet->alliance_id;
            $planet->alliance_id = $movement->planetTo->alliance->id;
        }

        if($movement->planetFrom->alliance) {
            $planet = Planet::find($movement->planetTo->id);
            $currentAlly = $planet->alliance_id;
            $planet->alliance_id = $movement->planetFrom->alliance->id;
        }

        IntelChange::create([
            'planet_id' => $planet->id,
            'alliance_from_id' => $currentAlly,
            'alliance_to_id' => $planet->alliance_id,
            'tick' => $this->tick
        ]);

        $planet->save();

        return;
    }

    public function seen($id)
    {
        IntelChange::where('id', $id)->update(['is_seen' => 1]);

        return $this->index();
    }
}
