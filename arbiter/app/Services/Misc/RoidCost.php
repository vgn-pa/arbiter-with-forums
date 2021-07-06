<?php

namespace App\Services\Misc;

class RoidCost
{
    private $roids;
    private $cost;
    private $bonus;

    public function setRoids($roids)
    {
        $this->roids = $roids;
        return $this;
    }

    public function setCost($cost)
    {
        $this->cost = $cost;
        return $this;
    }

    public function setBonus($bonus)
    {
        $this->bonus = $bonus;
        return $this;
    }

    public function execute()
    {
        $roids = $this->roids;
        $cost = short2num($this->cost);
        $bonus = $this->bonus;

        $mining = ($bonus) ? 250 * (1+($bonus/100)) : 250;

        $ticks = ($cost*100)/($roids*$mining);

        return "Capping " . $roids . " roids at " . number_shorten($cost, 1) . " value with " . $bonus . "% bonus will repay in " . (int) $ticks . " ticks";
    }
}
