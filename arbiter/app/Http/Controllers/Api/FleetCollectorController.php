<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\ScanQueue;
use Carbon\Carbon;
use App\Planet;
use App\Scan;
use App\FleetMovement;

class FleetCollectorController extends ApiController
{
    public function __construct()
    {

    }
    
    public function index(Request $request)
    {

    }

    public function store(Request $request)
    {
        $fleets = $request->all();

        foreach($fleets as $fleet) {
            $to = Planet::where([
                'x' => $fleet['to_x'],
                'y' => $fleet['to_y'],
                'z' => $fleet['to_z'],
            ])->first();
            $from = Planet::where([
                'x' => $fleet['from_x'],
                'y' => $fleet['from_y'],
                'z' => $fleet['from_z'],
            ])->first();

            $fm = [
              'fleet_name' => $fleet['fleet'],
              'planet_from_id' => $from->id,
              'planet_to_id' => $to->id,
              'mission_type' => $fleet['type'],
              'land_tick' => $fleet['tick'] + $fleet['eta'],
              'tick' => $fleet['tick'],
              'eta' => $fleet['eta'],
              'ship_count' => $fleet['ships'],
              'source' => 'parser'
            ];

            if(!$movement = FleetMovement::where([
              'land_tick'   => $fleet['tick'] + $fleet['eta'],
              'planet_from_id' => $from->id,
              'planet_to_id' => $to->id
            ])->first()) {
                FleetMovement::insert($fm);
            } else {
                $fm = array_merge($movement->toArray(), $fm);
                FleetMovement::where('id', $movement->id)->update($fm);
            }
        }

        return response()->json();
    }
}
