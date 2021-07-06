<?php

namespace App\Services\Misc;

use Config;
use App\Services\ParseCoordsList;
use App\Planet;
use App\Tick;

class MakeBattleCalc
{
    private $coordParser;

    private $link = "https://game.planetarion.com/bcalc.pl?";

    private $defPlanets = [];

    private $attPlanets = [];

    private $x;
    private $y;
    private $z;

    private $tick;

    private $params = [];

    private $races = [
        'Ter' => 1,
        'Cat' => 2,
        'Xan' => 3,
        'Zik' => 4,
        'Etd' => 5
    ];

    public function __construct(ParseCoordsList $coordParser)
    {
        $this->coordParser = $coordParser;
        $this->tick = Tick::orderBy('tick')->first();
    }

    public function setX($x)
    {
        $this->x = $x;

        return $this;
    }

    public function setY($y)
    {
        $this->y = $y;

        return $this;
    }

    public function setZ($z)
    {
        $this->z = $z;

        return $this;
    }

    public function setDefPlanets($planets)
    {
        $this->defPlanets = $this->coordParser->setCoords($planets)->execute();

        return $this;
    }

    public function setAttPlanets($planets)
    {
        $this->attPlanets = $this->coordParser->setCoords($planets)->execute();

        return $this;
    }

    public function execute()
    {
        $this->addPlanet($this->x, $this->y, $this->z, 'def', 1);

        $defNum = 1;

        foreach($this->defPlanets as $def) {
            $defNum++;
            $this->addPlanet($def['x'], $def['y'], $def['z'], 'def', $defNum);
        }

        $this->params['def_fleets'] = $defNum;

        $attNum = 0;

        foreach($this->attPlanets as $att) {
            $attNum++;
            $this->addPlanet($att['x'], $att['y'], $att['z'], 'att', $attNum);
        }

        $this->params['att_fleets'] = $attNum;

        return $this->link . http_build_query($this->params);
    }

    private function addPlanet($x, $y, $z, $type, $num)
    {
        $planet = Planet::with([
            'latestP',
            'latestP.scan',
            'latestD',
            'latestD.scan',
            'latestU',
            'latestU.u',
            'latestU.u.ship',
            'latestA',
            'latestA.au',
            'latestA.au.ship',
          ])
          ->where(['x' => $x, 'y' => $y, 'z' => $z])
          ->first();

        $scan = null;

        $this->params[$type.'_planet_value_'.$num] = $planet->getOriginal('value');
        $this->params[$type.'_planet_score_'.$num] = $planet->getOriginal('score');
        $this->params[$type.'_'.$num.'_race'] = $this->races[$planet->race];
        $this->params[$type.'_coords_x_'.$num] = $planet->x;
        $this->params[$type.'_coords_y_'.$num] = $planet->y;
        $this->params[$type.'_coords_z_'.$num] = $planet->z;

        if($type == 'def' && $num == 1) {
            if($planet->latestP && $planet->latestP->scan->tick >= ($this->tick->tick-24)) {
                $this->params[$type.'_metal_asteroids'] = $planet->latestP->roid_metal;
                $this->params[$type.'_crystal_asteroids'] = $planet->latestP->roid_crystal;
                $this->params[$type.'_eonium_asteroids'] = $planet->latestP->roid_eonium;
            }
        }

        if($planet->latestA && $planet->latestA->tick >= ($this->tick->tick-24)) { $scan = 'latestA'; $deet = "au"; }
        if(((!$planet->latestA || $planet->latestA->tick < ($this->tick->tick-24)) && $planet->latestU && $planet->latestU->tick >= ($this->tick->tick-24))) { $scan = 'latestU'; $deet = "u"; }

        if($scan) {
            foreach($planet->{$scan}->{$deet} as $ship) {
                $id = (int) $ship->ship->id-1;
                $this->params[$type.'_'.$num.'_'.$id] = $ship->amount;
            }
        }
    }
}
