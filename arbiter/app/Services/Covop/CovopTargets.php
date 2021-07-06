<?php

namespace App\Services\Covop;

use Illuminate\Console\Command;
use App\Planet;
use App\PlanetHistory;
use App\Tick;
use DB;

class CovopTargets {

    private $hasDev;
    private $population;
    private $ally;
    private $agents;
    private $stealth;

    public function setHasDev($hasDev)
    {
        $this->hasDev = ($hasDev == 'false') ? false : true;
        return $this;
    }

    public function excludeAlly($ally)
    {
        $this->excludeAlly = $ally;
        return $this;
    }

    public function setPopulation($population)
    {
        $this->population = $population;
        return $this;
    }

    public function setStealth($stealth)
    {
        $this->stealth = $stealth;
        return $this;
    }

    public function setAgents($agents)
    {
        $this->agents = $agents;
        return $this;
    }

    public function execute()
    {
        $tick = Tick::orderBy('tick', 'DESC')->first();

        $planets = Planet::where('covop_hit', 0)->with(['latestP', 'latestP.scan', 'latestD', 'latestD.scan'])
            ->whereHas('latestP.scan', function($query) use($tick) {
              $query->where('tick', '>', $tick->tick-24);
            });

        if($this->excludeAlly) {
            $planets = $planets->whereRaw(DB::raw("(alliance_id != ? OR alliance_id is null)"), [$this->excludeAlly]);
        }

        if($this->hasDev) {
            $planets = $planets->whereHas('latestD.scan', function($query) use($tick) {
                $query->where('tick', '>', $tick->tick-24);
            });
        }

        $planets = $planets->get();

        foreach($planets as $planet) {

            $planet->history = PlanetHistory::where('planet_id', $planet->id)->orderBy('tick', 'DESC')->limit(5)->get();

            $secCentres = 0;

            if($this->hasDev) $secCentres = (int) $planet->latestD->security_centre;

            $totalCons = (int) $planet->total_cons;
            $guards = (int) str_replace(',', '', $planet->latestP->guards);
            $roidCount = (int) str_replace(',', '', $planet->latestP->roid_metal) + (int) str_replace(',', '', $planet->latestP->roid_crystal) + (int) str_replace(',', '', $planet->latestP->roid_eonium);

            $first = (50+5*(min(($guards/($roidCount+1)), 15)));
            
            $min = (1+(($secCentres*2.75) + -10 + 0)/100);
            $max = (1+(($secCentres*2.75) + 25 + $this->population)/100);

            $minAlert = $first * $min;
            $maxAlert = $first * $max;

            $planet->max_success = $this->calcCovop($maxAlert);
            $planet->min_success = $this->calcCovop($minAlert);

            $planet->min_alert = (int) $minAlert;
            $planet->max_alert = (int) $maxAlert;
        }

        $planets = $planets->sortBy('max_alert');

        return $planets->values();
    }

    private function calcCovop($alert) 
    {
        $stealth = $this->stealth;
        $agents = $this->agents;

        $newStealth = ($stealth - 5 - intval($agents*0.5));
        $result = (1 + $newStealth) - $alert;

        if($result > 1) return true;

        return false;
    }
}
