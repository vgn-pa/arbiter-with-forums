<?php

namespace App\Services;

use App\PlanetHistory;
use App\Scan;
use App\ScanQueue;
use App\PlanetScan;
use App\DevelopmentScan;
use App\UnitScan;
use App\AdvancedUnitScan;
use App\NewsScan;
use App\JgpScan;
use App\Ship;
use App\Planet;
use App\Tick;
use App\FleetMovement;
use App\IntelChange;
use App\Services\Scans\PlanetScanParser;
use App\Services\Scans\DevelopmentScanParser;
use App\Services\Scans\UnitScanParser;
use App\Services\Scans\NewsScanParser;
use App\Services\Scans\JumpgateScanParser;
use App\Services\Scans\AdvancedUnitScanParser;
use Config;

use Artisan;

class ScanParser
{
    protected $parsed = [
        'p' => 0,
        'd' => 0,
        'u' => 0,
        'au' => 0,
        'n' => 0,
        'j' => 0
    ];

    private $tick = 0;

    public function parse($url)
    {
        $allScans = explode(PHP_EOL, $url);

        // If more than 1 scan link, queue for parsing
        if(count($allScans) > 1) {
            $ids = [];
            foreach($allScans as $url) {
                $ids[] = str_replace(Config::get('pa.scan_url'), '', $url);
            }

            $ids = array_unique($ids);

            $existing = ScanQueue::whereIn('scan_id', $ids)->get()->keyBy('scan_id')->toArray();

            $newScans = [];

            foreach($ids as $id) {
                if(!isset($existing[$id])) {
                    $newScans[] = [
                        'scan_id' => $id
                    ];
                }
            }

            ScanQueue::insert($newScans);

            $msg = count($newScans) . " new scans detected";

            if(count($newScans)) $msg .= ", queued for parsing";
            $parsed[] = $msg;

            return $parsed;
        }

        $parsed = [];

        $tick = Tick::orderBy('tick', 'DESC')->first();

        $this->tick = $tick;

        $ps = Planet::all();

        $planets = [];

        foreach($ps as $planet) {
            $planets[$planet->x . ":" . $planet->y . ":" . $planet->z] = $planet;
        }

        foreach($allScans as $url) {
            preg_match('!https://[^/]+/(?:showscan|waves).pl\?scan_id=([0-9a-zA-Z]+)!', $url, $single);
            preg_match('!https://[^/]+/(?:showscan|waves).pl\?scan_grp=([0-9a-zA-Z]+)!', $url, $group);

            if($single) {
                // Check if scan is already in DB
                $id = $single[1];
                if(Scan::where('pa_id', $id)->first()) continue;
            }

            try {
                $html = file_get_contents($url);
            } catch(\Exception $e) {
                return response()->json(['error' => 'Couldn\'t read url'], 422);
            }

            $scans = explode("<hr>", $html);

            if($group) {
                unset($scans[0]);
            } else {
                unset($scans[1]);
            }

            $count = 0;

            foreach($scans as $scan) {

                preg_match("/scan_id=([0-9a-zA-Z]+)/", $scan, $id);

                if(isset($id[1])) {
                    $scanId = $id[1];

                    preg_match('/>Scan time\: .* (\d+\:\d+\:\d+)/', $scan, $time);
                    preg_match('/>([^>]+) on (\d+)\:(\d+)\:(\d+) in tick (\d+)/', $scan, $tick);

                    if(!$tick) continue;

                    $scanType = strtoupper($tick[1]);
                    $x = $tick[2];
                    $y = $tick[3];
                    $z = $tick[4];
                    $tick = $tick[5];
                    $time = $time[1];

                    // Planet gone
                    if(!isset($planets[$x . ":" . $y . ":" . $z])) continue;

                    $planet = $planets[$x . ":" . $y . ":" . $z];

                    $planetId = ($planet) ? $planet->id : null;

                    if($planetId) {
                        $scan = preg_replace("/\s+/", "", $scan);

                        // If scan exists or its already queued for parsing, skip it
                        if($existingScan = Scan::where('pa_id', $scanId)->first()) continue;

                        // If scan exists in the queue already, then flag it processed and continue processing
                        if($existingQueue = ScanQueue::where('scan_id', $scanId)->first()) {
                            $existingQueue->processed = 1;
                            $existingQueue->save();
                        }
                        // Scan isnt in queue, lets add the id and mark it processed so it doesnt get queued again
                        else {
                            ScanQueue::create([
                                'scan_id' => $scanId,
                                'processed' => 1
                            ]);
                        }

                        if($scanType == 'PLANET SCAN') {
                            $planetScan = new PlanetScanParser();
                            $planetScan = $planetScan->setScan($scan)
                              ->setScanId($scanId)
                              ->setPlanetId($planetId)
                              ->setTick($tick)
                              ->setTime($time)
                              ->execute();

                            if($planetScan) $this->parsed['p']++;
                        }

                        if($scanType == 'DEVELOPMENT SCAN') {
                            $devScan = new DevelopmentScanParser();
                            $devScan = $devScan->setScan($scan)
                              ->setScanId($scanId)
                              ->setPlanetId($planetId)
                              ->setTick($tick)
                              ->setTime($time)
                              ->execute();

                            if($devScan) $this->parsed['d']++;
                        }

                        if($scanType == 'UNIT SCAN') {
                            $unitScan = new UnitScanParser();
                            $unitScan = $unitScan->setScan($scan)
                              ->setScanId($scanId)
                              ->setPlanetId($planetId)
                              ->setTick($tick)
                              ->setTime($time)
                              ->execute();

                            if($unitScan) $this->parsed['u']++;
                        }

                        if($scanType == 'ADVANCED UNIT SCAN') {
                            $auScan = new AdvancedUnitScanParser();
                            $auScan = $auScan->setScan($scan)
                              ->setScanId($scanId)
                              ->setPlanetId($planetId)
                              ->setTick($tick)
                              ->setTime($time)
                              ->execute();

                            if($auScan) $this->parsed['au']++;
                        }

                        if($scanType == 'NEWS SCAN') {
                            $newsScan = new NewsScanParser();
                            $newsScan = $newsScan->setScan($scan)
                              ->setScanId($scanId)
                              ->setPlanetId($planetId)
                              ->setTick($tick)
                              ->setTime($time)
                              ->execute();

                            if($newsScan) $this->parsed['n']++;
                        }

                        if($scanType == 'JUMPGATE PROBE') {
                            $jumpgateScan = new JumpgateScanParser();
                            $jumpgateScan = $jumpgateScan->setScan($scan)
                              ->setScanId($scanId)
                              ->setPlanetId($planetId)
                              ->setTick($tick)
                              ->setTime($time)
                              ->execute();

                            if($jumpgateScan) $this->parsed['j']++;
                        }
                    }
                }
            }
        }

        if($this->parsed['p']) { $parsed[] = $this->parsed['p'] . " planet scans parsed"; }
        if($this->parsed['d']) { $parsed[] = $this->parsed['d'] . " development scans parsed"; }
        if($this->parsed['u']) { $parsed[] = $this->parsed['u'] . " unit scans parsed"; }
        if($this->parsed['au']) { $parsed[] = $this->parsed['au'] . " advanced unit scans parsed"; }
        if($this->parsed['n']) { $parsed[] = $this->parsed['n'] . " news scans parsed"; }
        if($this->parsed['j']) { $parsed[] = $this->parsed['j'] . " jumpgate probes parsed"; }

        if(!$parsed) {
            $parsed[] = "No scans parsed";
        }

        if($this->parsed['p'] || $this->parsed['d']) Artisan::call('pa:calcAlert');
        if($this->parsed['p']) Artisan::call('pa:hiddenResources');

        return $parsed;
    }
}
