<?php

namespace App\Services\Misc;

use Config;
use App\Ship;

class Cost
{
    private $ship;
    private $amount;

    public function setShip($ship)
    {
        $this->ship = $ship;
        return $this;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function execute()
    {
        $ship = $this->ship;
        $amount = short2num($this->amount);

        if(intval($ship)) {
            $ship = Ship::find($ship);
        } else {
            if(Ship::where('name', 'LIKE', '%' . $ship . '%')->count() == 1) {
                $ship = Ship::where('name', 'LIKE', '%' . $ship . '%')->first();
            } else {
                if(Ship::where('name', 'LIKE', '%' . $ship . '%')->count() == 0) {
                    return "Can't find a ship with that name";
                } else {
                    return "Ship name is too ambiguous";
                }
            }
        }

        $metal = number_shorten($ship->metal * $amount, 1);
        $crystal = number_shorten($ship->crystal * $amount, 1);
        $eonium = number_shorten($ship->eonium * $amount, 1);

        $reply = sprintf("Buying %s %s will cost %s metal, %s crystal and %s eonium.\n",
            number_format($amount),
            $ship->name,
            $metal,
            $crystal,
            $eonium
        );

        $governments = Config::get('governments');

        foreach($governments as $name => $gov) {
            $metal = number_shorten($ship->metal * (1+$gov['prod_cost']) * $amount, 1);
            $crystal = number_shorten($ship->crystal * (1+$gov['prod_cost']) * $amount, 1);
            $eonium = number_shorten($ship->eonium * (1+$gov['prod_cost']) * $amount, 1);


            $reply .= sprintf("%s: %s metal, %s crystal and %s eonium.\n",
                $name,
                $metal,
                $crystal,
                $eonium
            );
        }

        return $reply;
    }
}
