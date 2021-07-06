<?php

namespace App\Services;

use App\PlanetHistory;
use App\Alliance;
use App\Planet;
use App\Scan;
use App\PlanetScan;
use App\DevelopmentScan;
use App\UnitScan;
use App\AdvancedUnitScan;
use App\Ship;
use Artisan;

class IntelParser
{
    public function parse($xml)
    {
        preg_match("/<intel>(.*?)<\/intel>/si", $xml, $matches);

        if(empty($matches)) return abort();

        $xml = str_replace('<NONE>', '', $matches[0]);
        $xml = str_replace('<DELETED>', '', $xml);

        $xml = preg_replace("/<ruler>(.*?)<\/ruler>/", "", $xml);
        $xml = preg_replace("/<planet>(.*?)<\/planet>/", "", $xml);

        $intel = simplexml_load_string($xml);

        if($intel) {

            Planet::whereHas('alliance', function($query) {
                $query->where('is_locked', 0);
            })->whereNotNull('id')->update([
                'alliance_id' => null
            ]);

            foreach($intel as $item) {

                $planet = "";
                $alliance = "";
                $nick = "";

                if($item->coords != "::") {

                    if($item->alliance[0] != "[]" || $item->nick[0]) {

                        if($item->nick[0]) {

                            $nick = $item->nick[0];

                        }

                        preg_match("/(\d+):(\d+):(\d+)/", $item->coords, $coords);

                        $planet = Planet::where([
                              'x' => $coords[1],
                              'y' => $coords[2],
                              'z' => $coords[3]
                        ])->first();

                        if($planet) {

                            if($item->alliance[0] != "[]") {
                                $alliance = Alliance::where('name', $item->alliance[0])->first();
                                $id = ($alliance) ? $alliance->id : null;
                                if($id && !$alliance->is_locked) {
                                    $planet->alliance_id = $id;
                                }
                            }

                            $planet->nick = $nick;
                            $planet->save();
                        }
                    }
                }
            }
            Artisan::call('pa:checkIntel');
        }
    }
}
