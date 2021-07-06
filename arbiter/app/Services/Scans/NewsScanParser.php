<?php

namespace App\Services\Scans;

use App\Scan;
use App\NewsScan;
use App\FleetMovement;
use App\IntelChange;
use App\Planet;
use App\ScanRequest;
use App\Services\Scans\ScanTrait;

class NewsScanParser
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

        preg_match_all('!<tr[^>]*><td[^>]*>Launch<\/td><td[^>]*>(\d+)<\/td><td[^>]*>The(\w+)fleethasbeenlaunched,headingfor<a[^>]*>(\d+):(\d+):(\d+)</a>,onamissionto(\w+)\.Arrivaltick:(\d+)<\/td><\/tr>!', $scan, $launches);

        $count = count($launches[0]);

        $index = 0;

        for($int = 0; $int < $count; $int++) {
            $x = $launches[3][$int];
            $y = $launches[4][$int];
            $z = $launches[5][$int];
            $planetTo = Planet::where(['x' => $x, 'y' => $y, 'z' => $z])->first();

            if($planetTo) {
                $movement[$index]['launch_tick'] = $launches[1][$int];
                $movement[$index]['fleet_name'] = $launches[2][$int];
                $movement[$index]['mission_type'] = $launches[6][$int];
                $movement[$index]['land_tick'] = $launches[7][$int];
                $movement[$index]['planet_from_id'] = $planetId;
                $movement[$index]['planet_to_id'] = $planetTo->id;
                $movement[$index]['tick'] = $movement[$index]['launch_tick'];
                $movement[$index]['eta'] = ($movement[$index]['land_tick'] - $movement[$index]['launch_tick']) +1;
                $movement[$index]['source'] = 'launch';
                $index++;
            }
        }

        preg_match_all('!<tr[^>]*><td[^>]*>Incoming<\/td><td[^>]*>(\d+)<\/td><td[^>]*>Wehavedetectedanopenjumpgatefrom(\w+),locatedat<a[^>]*>(\d+):(\d+):(\d+)<\/a>.Thefleetwillapproachoursystemintick(\d+)andappearstohave(\d+)visibleships.<\/td><\/tr>!', $scan, $incomings);

        $count = count($incomings[0]);

        for($int = 0; $int < $count; $int++) {
            $x = $incomings[3][$int];
            $y = $incomings[4][$int];
            $z = $incomings[5][$int];
            $planetFrom = Planet::where(['x' => $x, 'y' => $y, 'z' => $z])->first();

            if($planetFrom) {
                $movement[$index]['launch_tick'] = $incomings[1][$int];
                $movement[$index]['fleet_name'] = $incomings[2][$int];
                $movement[$index]['land_tick'] = $incomings[6][$int];
                $movement[$index]['planet_from_id'] = $planetFrom->id;
                $movement[$index]['planet_to_id'] = $planetId;
                $movement[$index]['tick'] = $movement[$index]['launch_tick'];
                $movement[$index]['eta'] = ($movement[$index]['land_tick'] - $movement[$index]['launch_tick']) +1;
                $movement[$index]['ship_count'] = $incomings[7][$int];
                $movement[$index]['source'] = 'incoming';
                $index++;
            }
        }

        foreach($movement as $fleet) {
            if(!$movement = FleetMovement::where([
              'land_tick'   => $fleet['land_tick'],
              'planet_from_id' => $fleet['planet_from_id'],
              'planet_to_id' => $fleet['planet_to_id']
            ])->first()) {
                FleetMovement::create($fleet);
                // If the fleet movement was eta 7 then check intel of the to/from
                if($fleet['eta'] == 7) {
                    $planetFrom = Planet::find($fleet['planet_from_id']);
                    $planetTo = Planet::find($fleet['planet_to_id']);
                    if(!$planetFrom->alliance_id && $planetTo->alliance_id) {
                        $planetFrom->alliance_id = $planetTo->alliance_id;
                        $planetFrom->save();
                        IntelChange::create([
                            'planet_id' => $planetFrom->id,
                            'alliance_from_id' => null,
                            'alliance_to_id' => $planetTo->alliance_id,
                            'tick' => $tick
                        ]);
                    }
                    if(!$planetTo->alliance_id && $planetFrom->alliance_id) {
                        $planetTo->alliance_id = $planetFrom->alliance_id;
                        $planetFrom->save();
                        IntelChange::create([
                            'planet_id' => $planetTo->id,
                            'alliance_from_id' => null,
                            'alliance_to_id' => $planetTo->alliance_id,
                            'tick' => $tick
                        ]);
                    }
                }
            } else {
                $fleet = array_merge($movement->toArray(), $fleet);
                FleetMovement::where('id', $movement['id'])->update($fleet);
            }
        }

        $newScan = Scan::create([
            'pa_id'     => $scanId,
            'planet_id' => $planetId,
            'scan_type' => NewsScan::class,
            'tick'      => $tick,
            'time'      => $time
        ]);

        $news = NewsScan::create([
            'scan_id' => $newScan->id
        ]);

        if($request = ScanRequest::with(['planet', 'user'])->where(['planet_id' => $planetId, 'scan_type' => 'n'])->get()) {
            foreach($request as $req) {
                $req->scan_id = $newScan->id;
                $req->save();
                $this->requestFulfilled($req);
            }
        }

        $planet = Planet::with('latestN', 'latestN.scan')->where('id', $planetId)->first();

        if((isset($planet->latestN) && $planet->latestN->scan->tick < $tick) || !$planet->latest_N || $planet->latestN->scan->time < $newScan->time) {
            $planet->latest_N = $news->id;
            $planet->save();
        }

        return true;
    }
}
