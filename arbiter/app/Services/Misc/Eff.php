<?php

namespace App\Services\Misc;

use App\Ship;

class Eff
{
    private $id;
    private $amount;
    private $name;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setName($ship)
    {
        $this->name = $ship;
        return $this;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function execute()
    {
        $amount = short2num($this->amount);

        if($this->name) {
            if(Ship::where('name', 'LIKE', '%' . $this->name . '%')->count() == 1) {
                $ship = Ship::where('name', 'LIKE', '%' . $this->name . '%')->first();
            } else {
                if(Ship::where('name', 'LIKE', '%' . $this->name . '%')->count() == 0) {
                    return "Can't find a ship with that name";
                } else {
                    // Check for actual name
                    if($ship = Ship::where('name', 'LIKE', '%' . $this->name)->count() == 1) {
                        $ship = Ship::where('name', 'LIKE', '%' . $this->name)->first();
                    } 
                    // Too many matches
                    else {
                        $ships = Ship::where('name', 'LIKE', '%' . $this->name . '%')->get()->pluck('name')->toArray();
                        return "Ship name is too ambiguous (" . implode(", ", $ships) . ")";
                    }
                }
            }
        } 

        if($this->id) $ship = Ship::find($this->id);

        $damage = $ship->damage * $amount;
        $shots = $ship->guns * $amount;

        $shipValue = number_shorten((($ship->metal + $ship->crystal + $ship->eonium) * $amount) / 100, 1);

        $t1 = $ship->t1;
        $t2 = $ship->t2;
        $t3 = $ship->t3;

        $t1s = [];
        $t2s = [];
        $t3s = [];

        switch($ship->type) {
            case 'EMP': $kill = 'hug';
              break;
            case 'Steal': $kill = 'steal';
              break;
            default: $kill = 'kill';
              break;
        }

        if($t1 == 'Roids') {
            $killed = (int) ($damage / 50);
            $reply = sprintf('%s %s (%s) will capture %s roids', number_format($amount), $ship->name, $shipValue, $killed);
            return $reply;
        }

        if($t1 == 'Structure') {
          $killed = (int) ($damage / 500);
          $reply = sprintf('%s %s (%s) will destroy %s structures', number_format($amount), $ship->name, $shipValue, $killed);
          return $reply;
        }

        $targets = $this->getTargets($ship);

        $reply = sprintf('%s %s (%s) will %s ', number_format($amount), $ship->name, $shipValue, $kill);

        $dmgMultiplier = [
            1 => 1,
            2 => 0.7,
            3 => 0.5
        ];

        foreach($targets as $t => $target) {

            $t++;

            $targetting = Ship::where('class', $target)->get();

            foreach($targetting as $tgt) {
                $killed = 0;

                if($ship->type != 'EMP') {
                    $killed = (int) ($damage / $tgt->armor);
                }

                if($ship->type == 'EMP') {
                    $killed = (int) ($shots) * (100 - $tgt->empres)/100;
                }

                $killed = $killed * $dmgMultiplier[$t];

                $value = number_shorten((($tgt->metal + $tgt->crystal + $tgt->eonium) * $killed) / 100, 1);

                $reply .= sprintf('%s %s (%s) | ', number_format($killed), $tgt->name, $value);
            }

        }

        return substr($reply, 0, -3);
    }

    private function getTargets($ship)
    {
        $targets = [
            $ship->t1
        ];

        if($ship->t2) $targets[] = $ship->t2;
        if($ship->t3) $targets[] = $ship->t3;

        return $targets;
    }
}
