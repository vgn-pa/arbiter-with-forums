<?php

namespace App\Services\Scans;

use App\Scan;
use App\PlanetScan;
use App\Planet;
use App\ScanRequest;
use App\Services\Scans\ScanTrait;

class PlanetScanParser
{
    use ScanTrait;

    public function execute()
    {
        $scan = $this->scan;
        $scanId = $this->scanId;
        $planetId = $this->planetId;
        $tick = $this->tick;
        $time = $this->time;

        $resourcesRegex = "!<tr><td[^>]*>Metal<\/td><td[^>]*>(\d+|\d{1,3}(,\d{3})*)(\.\d+)?<\/td><td[^>]*>(\d+|\d{1,3}(,\d{3})*)(\.\d+)?<\/td><\/tr><tr><td[^>]*>Crystal<\/td><td[^>]*>(\d+|\d{1,3}(,\d{3})*)(\.\d+)?<\/td><td[^>]*>(\d+|\d{1,3}(,\d{3})*)(\.\d+)?<\/td><\/tr><tr><td[^>]*>Eonium<\/td><td[^>]*>(\d+|\d{1,3}(,\d{3})*)(\.\d+)?<\/td><td[^>]*>(\d+|\d{1,3}(,\d{3})*)(\.\d+)?<\/td><\/tr>!";
        $guardsRegex = "!<tr><th[^>]*>Agents<\/th><th[^>]*>SecurityGuards<\/th><\/tr><tr><td[^>]*>([^<]+)<\/td><td[^>]*>([^<]+)<\/td><\/tr>!";
        $factoriesRegex = "!<tr><th[^>]*>Light<\/th><th[^>]*>Medium<\/th><th[^>]*>Heavy<\/th><\/tr><tr><td[^>]*>([^<]+)<\/td><td[^>]*>([^<]+)<\/td><td[^>]*>([^<]+)<\/td><\/tr>!";
        $prodRegex = "!TotalAmountofResourcesinProduction:<span[^>]*>(\d+|\d{1,3}(,\d{3})*)(\.\d+)?<\/span>!";

        preg_match($resourcesRegex, $scan, $resources);
        preg_match($guardsRegex, $scan, $guards);
        preg_match($factoriesRegex, $scan, $factories);
        preg_match($prodRegex, $scan, $prod);

        //dd($scan, $resourcesRegex, $guardsRegex, $factoriesRegex, $prodRegex);

        $newScan = Scan::create([
            'pa_id'     => $scanId,
            'planet_id' => $planetId,
            'scan_type' => PlanetScan::class,
            'tick'      => $tick,
            'time'      => $time
        ]);

        $planetScan = PlanetScan::create([
            'scan_id' => $newScan->id,
            'roid_metal' => $resources[1],
            'roid_crystal' => $resources[7],
            'roid_eonium' => $resources[13],
            'res_metal' => $resources[4],
            'res_crystal' => $resources[10],
            'res_eonium' => $resources[16],
            'agents' => $guards[1],
            'guards' => $guards[2],
            'factory_usage_light' => $factories[1],
            'factory_usage_medium' => $factories[2],
            'factory_usage_heavy' => $factories[3],
            'prod_res' => $prod[1]
        ]);

        if($request = ScanRequest::with(['planet', 'user'])->where(['planet_id' => $planetId, 'scan_type' => 'p'])->get()) {
            foreach($request as $req) {
                $req->scan_id = $newScan->id;
                $req->save();
                $this->requestFulfilled($req);
            }
        }

        $planet = Planet::with('latestP', 'latestP.scan')->where('id', $planetId)->first();

        if((isset($planet->latestP) && $planet->latestP->scan->tick < $tick) || !$planet->latest_p || $planet->latestP->scan->time < $newScan->time) {
            $planet->latest_p = $planetScan->id;
            $planet->save();
        }

        return true;
    }
}
