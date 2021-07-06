<?php

namespace App\Services\Scans;

use App\Scan;
use App\UnitScan;
use App\Planet;
use App\Ship;
use App\ScanRequest;
use App\Services\Scans\ScanTrait;

class UnitScanParser
{
    use ScanTrait;

    public function execute()
    {
        $scan = $this->scan;
        $scanId = $this->scanId;
        $planetId = $this->planetId;
        $tick = $this->tick;
        $time = $this->time;

        preg_match_all("!(\w+\s?\w*\s?\w*)</td><td[^>]*>(\d+(?:,\d{3})*)</td>!", $scan, $ships);

        $newScan = Scan::create([
            'pa_id'     => $scanId,
            'planet_id' => $planetId,
            'scan_type' => UnitScan::class,
            'tick'      => $tick,
            'time'      => $time
        ]);

        foreach($ships[1] as $key => $shipName) {

            // Put spaces back into ship names
            $shipName = trim(implode(" ", preg_split('/(?=[A-Z])/', $shipName)));

            $ship = Ship::where('name', $shipName)->first();

            $auScan = UnitScan::create([
                'scan_id' => $newScan->id,
                'ship_id' => $ship->id,
                'amount' => intval(str_replace(',', '', $ships[2][$key]))
            ]);
        }

        if($request = ScanRequest::with(['planet', 'user'])->where(['planet_id' => $planetId, 'scan_type' => 'u'])->get()) {
            foreach($request as $req) {
                $req->scan_id = $newScan->id;
                $req->save();
                $this->requestFulfilled($req);
            }
        }

        $planet = Planet::with('latestU')->where('id', $planetId)->first();

        if((isset($planet->latestU) && $planet->latestU->tick < $tick) || !$planet->latest_u || (isset($planet->latestU) && $planet->latestU->time < $newScan->time)) {
            $planet->latest_u = $newScan->id;
            $planet->save();
        }

        return true;
    }
}
