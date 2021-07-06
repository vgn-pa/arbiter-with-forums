<?php

namespace App\Services\Scans;

use App\Scan;
use App\JgpScan;
use App\Planet;
use App\FleetMovement;
use App\ScanRequest;
use App\Services\Scans\ScanTrait;

class JumpgateScanParser
{
    use ScanTrait;

    public function execute()
    {
        $scan = $this->scan;
        $scanId = $this->scanId;
        $planetId = $this->planetId;
        $tick = $this->tick;
        $time = $this->time;

        $movement = [];

        $newScan = Scan::create([
            'pa_id'     => $scanId,
            'planet_id' => $planetId,
            'scan_type' => JgpScan::class,
            'tick'      => $tick,
            'time'      => $time
        ]);

        $jgpScan = JgpScan::create([
            'scan_id' => $newScan->id
        ]);

        preg_match_all('!<tr[^>]*><td[^>]*><a[^>]*>(\d+):(\d+):(\d+)<\/a>\(<span[^>]*>(\w+)<\/span>\)<\/td><td[^>]*>(\w+)<\/td><td[^>]*>(\w+)<\/td><td[^>]*>(\d+)<\/td><td[^>]*>(\d+)<\/td><\/tr>!', $scan, $fleets);

        $count = count($fleets[0]);

        for($int = 0; $int < $count; $int++) {
            $x = $fleets[1][$int];
            $y = $fleets[2][$int];
            $z = $fleets[3][$int];
            $eta = $fleets[7][$int];
            $missionType = $fleets[5][$int];
            $fleetName = $fleets[6][$int];
            $shipCount = $fleets[8][$int];
            $planetFrom = Planet::where(['x' => $x, 'y' => $y, 'z' => $z])->first();
            $planetTo = Planet::find($planetId);
            $galMates = Planet::where('galaxy_id', $planetTo->galaxy_id)->get()->keyBy('id');

            if(!$planetFrom) continue;
            // Dont record returning fleets
            if($missionType == "Return") continue;
            // Dont record any attacks eta 8 and above (could be PL)
            if($missionType == "Attack" && $eta > 7) continue;
            // Dont include any defences eta 7 and above (could be PL)
            if($missionType == "Defend" && $eta > 6) continue;
            // Dont record eta 5 defences from gal mates (is PL)
            if($missionType == "Defend" && isset($galMates[$planetFrom->id]) && $eta == 5) continue;

            $movement[$int]['fleet_name'] = $fleetName;
            $movement[$int]['mission_type'] = $missionType;
            $movement[$int]['planet_from_id'] = $planetFrom->id;
            $movement[$int]['planet_to_id'] = $planetId;
            $movement[$int]['tick'] = $tick;
            $movement[$int]['eta'] = $eta;
            $movement[$int]['ship_count'] = $shipCount;
            $movement[$int]['source'] = 'jgp';
            $movement[$int]['land_tick'] = $tick + $movement[$int]['eta'];
        }

        foreach($movement as $fleet) {
            if(!$movement = FleetMovement::where([
              'land_tick'   => $fleet['land_tick'],
              'planet_from_id' => $fleet['planet_from_id'],
              'planet_to_id' => $fleet['planet_to_id']
            ])->first())
            FleetMovement::create($fleet);
        }

        if($request = ScanRequest::with(['planet', 'user'])->where(['planet_id' => $planetId, 'scan_type' => 'j'])->get()) {
            foreach($request as $req) {
                $req->scan_id = $newScan->id;
                $req->save();
                $this->requestFulfilled($req);
            }
        }

        $planet = Planet::with('latestJ', 'latestJ.scan')->where('id', $planetId)->first();

        if((isset($planet->latestJ) && $planet->latestJ->scan->tick < $tick) || !$planet->latest_j || $planet->latestJ->scan->time < $newScan->time) {
            $planet->latest_j = $jgpScan->id;
            $planet->save();
        }

        return true;
    }
}
