<?php

namespace App\Services\Scans;

use App\Scan;
use App\AdvancedUnitScan;
use App\Planet;
use App\Ship;
use App\ScanRequest;
use App\Services\Scans\ScanTrait;

class AdvancedUnitScanParser
{
    use ScanTrait;

    public function execute()
    {
        $scan = $this->scan;
        $scanId = $this->scanId;
        $planetId = $this->planetId;
        $tick = $this->tick;
        $time = $this->time;

        preg_match_all('!<tr><td[^>]*>(\w+\s?\w*\s?\w*)</td><td[^>]*>(\d+|\d{1,3}(,\d{3})*)(\.\d+)?</td></tr>!', $scan, $ships);

        $newScan = Scan::create([
            'pa_id'     => $scanId,
            'planet_id' => $planetId,
            'scan_type' => AdvancedUnitScan::class,
            'tick'      => $tick,
            'time'      => $time
        ]);

        foreach($ships[1] as $key => $shipName) {

            // Put spaces back into ship names
            $shipName = trim(implode(" ", preg_split('/(?=[A-Z])/', $shipName)));

            $ship = Ship::where('name', $shipName)->first();

            $auScan = AdvancedUnitScan::create([
                'scan_id' => $newScan->id,
                'ship_id' => $ship->id,
                'amount' => intval(str_replace(',', '', $ships[2][$key]))
            ]);
        }

        if($request = ScanRequest::with(['planet', 'user'])->where(['planet_id' => $planetId, 'scan_type' => 'a'])->get()) {
            foreach($request as $req) {
                $req->scan_id = $newScan->id;
                $req->save();
                $this->requestFulfilled($req);
            }
        }

        $planet = Planet::with('latestA')->where('id', $planetId)->first();

        if((isset($planet->latestA) && $planet->latestA->tick < $tick) || !$planet->latest_a || $planet->latestA->time < $newScan->time) {
            $planet->latest_au = $newScan->id;
            $planet->save();
        }

        return true;
    }
}
