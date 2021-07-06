<?php

namespace App\Services\Scans;

use App\Scan;
use App\DevelopmentScan;
use App\Planet;
use App\ScanRequest;
use App\Services\Scans\ScanTrait;

class DevelopmentScanParser
{
    use ScanTrait;

    public function execute()
    {
        $scan = $this->scan;
        $scanId = $this->scanId;
        $planetId = $this->planetId;
        $tick = $this->tick;
        $time = $this->time;

        $consRegex = "!<tr><td[^>]*>LightFactory<\/td><td[^>]*>(\d*)<\/td><\/tr><tr><td[^>]*>MediumFactory<\/td><td[^>]*>(\d*)<\/td><\/tr><tr><td[^>]*>HeavyFactory<\/td><td[^>]*>(\d*)<\/td><\/tr><tr><td[^>]*>WaveAmplifier<\/td><td[^>]*>(\d*)<\/td><\/tr><tr><td[^>]*>WaveDistorter<\/td><td[^>]*>(\d*)<\/td><\/tr><tr><td[^>]*>MetalRefinery<\/td><td[^>]*>(\d*)<\/td><\/tr><tr><td[^>]*>CrystalRefinery<\/td><td[^>]*>(\d*)<\/td><\/tr><tr><td[^>]*>EoniumRefinery<\/td><td[^>]*>(\d*)<\/td><\/tr><tr><td[^>]*>ResearchLaboratory<\/td><td[^>]*>(\d*)<\/td><\/tr><tr><td[^>]*>FinanceCentre<\/td><td[^>]*>(\d*)<\/td><\/tr><tr><td[^>]*>MilitaryCentre<\/td><td[^>]*>(\d*)<\/td><\/tr><tr><td[^>]*>SecurityCentre<\/td><td[^>]*>(\d*)<\/td><\/tr>!";
        $sdRegex = "!<tr><td[^>]*>StructureDefence<\/td><td[^>]*>(\d*)<\/td><\/tr>!";
        $techRegex = "!<tr><td[^>]*>SpaceTravel</td><td[^>]*>(\d+).*</td></tr><tr><td[^>]*>Infrastructure</td><td[^>]*>(\d+).*</td></tr><tr><td[^>]*>Hulls</td><td[^>]*>(\d+).*</td></tr><tr><td[^>]*>Waves</td><td[^>]*>(\d+).*</td></tr><tr><td[^>]*>CoreExtraction</td><td[^>]*>(\d+).*</td></tr><tr><td[^>]*>CovertOps</td><td[^>]*>(\d+).*</td></tr><tr><td[^>]*>AsteroidMining</td><td[^>]*>(\d+).*</td></tr><tr><td[^>]*>PopulationManagement</td><td[^>]*>(\d+).*</td></tr>!";

        preg_match($consRegex, $scan, $structures);
        preg_match($sdRegex, $scan, $sd);
        preg_match($techRegex, $scan, $tech);

        $newScan = Scan::create([
            'pa_id'     => $scanId,
            'planet_id' => $planetId,
            'scan_type' => DevelopmentScan::class,
            'tick'      => $tick,
            'time'      => $time
        ]);

        $devScan = DevelopmentScan::create([
              'scan_id' => $newScan->id,
              'light_factory' => $structures[1],
              'medium_factory' => $structures[2],
              'heavy_factory' => $structures[3],
              'wave_amplifier' => $structures[4],
              'wave_distorter' => $structures[5],
              'metal_refinery' => $structures[6],
              'crystal_refinery' => $structures[7],
              'eonium_refinery' => $structures[8],
              'research_lab' => $structures[9],
              'finance_centre' => $structures[10],
              'military_centre' => $structures[11],
              'security_centre' => $structures[12],
              'structure_defence' => isset($sd[1]) ? $sd[1] : 0,
              'travel' => $tech[1],
              'infrastructure' => $tech[2],
              'hulls' => $tech[3],
              'waves' => $tech[4],
              'core' => $tech[5],
              'covert_op' => $tech[6],
              'mining' => $tech[7],
              'population' => $tech[8],
        ]);

        $cons = $devScan->light_factory
            + $devScan->medium_factory
            + $devScan->heavy_factory
            + $devScan->wave_amplifier
            + $devScan->wave_distoryer
            + $devScan->metal_refinery
            + $devScan->crystal_refinery
            + $devScan->eonium_refinery
            + $devScan->research_lab
            + $devScan->finance_centre
            + $devScan->security_centre
            + $devScan->structure_defence;

        Planet::where('id', $planetId)->update([
            'amps' => $devScan->wave_amplifier,
            'dists' => $devScan->wave_distorter,
            'waves' => $devScan->waves,
            'total_cons' => $cons
        ]);

        if($request = ScanRequest::with(['planet', 'user'])->where(['planet_id' => $planetId, 'scan_type' => 'd'])->get()) {
            foreach($request as $req) {
                $req->scan_id = $newScan->id;
                $req->save();
                $this->requestFulfilled($req);
            }
        }

        $planet = Planet::with('latestD', 'latestD.scan')->where('id', $planetId)->first();

        if((isset($planet->latestD) && $planet->latestD->scan->tick < $tick) || !$planet->latest_d || $planet->latestD->scan->time < $newScan->time) {
            $planet->latest_d = $devScan->id;
            $planet->save();
        }

        return true;
    }
}
