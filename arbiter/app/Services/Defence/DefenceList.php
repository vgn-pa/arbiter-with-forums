<?php

namespace App\Services\Defence;

use App\FleetMovement;
use App\User;
use App\Services\Misc\MakeBattleCalc;
use App;

class DefenceList
{
    private $tick;
    private $allianceId;
    private $query;

    public function __construct()
    {
        $this->query = FleetMovement::with([
            'planetTo' => function($q) {
                $q->select(['id', 'x', 'y', 'z', 'alliance_id']);
            }, 
            'planetFrom' => function($q) {
                $q->select(['id', 'x', 'y', 'z', 'alliance_id', 'latest_p', 'latest_d', 'latest_u', 'latest_au']);
            }, 
            'planetFrom.alliance'  => function($q) {
                $q->select(['id', 'name']);
            },
            'planetFrom.latestP',
            'planetFrom.latestP.scan',
            'planetFrom.latestD',
            'planetFrom.latestD.scan',
            'planetFrom.latestU',
            'planetFrom.latestU.u',
            'planetFrom.latestU.u.ship',
            'planetFrom.latestA',
            'planetFrom.latestA.au',
            'planetFrom.latestA.au.ship',
        ])->orderBy('land_tick', 'ASC');
    }

    public function setTick($tick)
    {
        $this->tick = $tick;
        return $this;
    }

    public function setAllianceId($id)
    {
        $this->allianceId = $id;
        return $this;
    }

    public function execute()
    {
        if(!$this->allianceId && !$this->tick) return;

        $incomings = $this->query->whereHas('planetTo', function($q) {
            $q->where('alliance_id', $this->allianceId);
        })->where('land_tick', '>', $this->tick)
        ->where('mission_type', 'Attack')
        ->get();

        $incs = $this->sortIncomingByEta($incomings);

        $incs = $this->addBattleCalcs($incs);

        $incomings = [];

        foreach($incs as $etaGroup => $eta) {
            $incomings[] = [
                'eta' => $etaGroup,
                'planets' => array_values($eta)
            ];
        }

        return collect(array_values($incomings));
    }

    private function sortIncomingByEta($incomings)
    {
        $incs = [];

        foreach($incomings as $inc) {
            $eta = $inc->land_tick - $this->tick;

            if(!isset($incs[$eta][$inc->planetTo->id])) {
                $user = User::where('planet_id', $inc->planetTo->id)->first();

                $incs[$eta][$inc->planetTo->id] = $inc->planetTo->toArray();

                // Add the webby user if there is one
                $incs[$eta][$inc->planetTo->id]['user'] = [];
                if($user) $incs[$eta][$inc->planetTo->id]['user'] = $user;
            }

            // Add fleets on this eta
            $incs[$eta][$inc->planetTo->id]['fleets'][] = $inc;
        }

        return $incs;
    }

    private function addBattleCalcs($incomings)
    {
        foreach($incomings as $etaGroup => $eta) {
            foreach($eta as $planetId => $planet) {

                $attackers = [];

                foreach($planet['fleets'] as $fleet) {
                    $attackers[] = $fleet->planetFrom->x . ":" . $fleet->planetFrom->y . ":" . $fleet->planetFrom->z;
                }

                $calc = App::make(MakeBattleCalc::class);
                $calc = $calc->setX($planet['x'])
                    ->setY($planet['y'])
                    ->setZ($planet['z'])
                    ->setAttPlanets(implode(" ", $attackers))
                    ->execute();

                $incomings[$etaGroup][$planetId]['calc'] = $calc;
            }
        }

        return $incomings;
    }
}
