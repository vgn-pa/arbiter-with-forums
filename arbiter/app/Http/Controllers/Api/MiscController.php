<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Auth;
use App\Ship;
use App\Services\Misc\Eff;
use App\Services\Misc\Stop;
use App\Services\Misc\RoidCost;
use App\Services\Misc\Afford;
use App\Services\Misc\Cost;
use App\Services\Misc\MakeBattleCalc;

class MiscController extends ApiController
{
    public function eff(Request $request, Eff $eff)
    {
        return $eff
          ->setId($request->input('ship'))
          ->setAmount($request->input('amount'))
          ->execute();
    }

    public function stop(Request $request, Stop $stop)
    {
        return $stop
          ->setId($request->input('ship'))
          ->setAmount($request->input('amount'))
          ->execute();
    }

    public function roidcost(Request $request, Roidcost $roidCost)
    {
        return $roidCost
            ->setRoids($request->input('roids'))
            ->setCost($request->input('cost'))
            ->setBonus($request->input('bonus', 0))
            ->execute();
    }

    public function afford(Request $request, Afford $afford)
    {
        return $afford
            ->setX($request->input('x'))
            ->setY($request->input('y'))
            ->setZ($request->input('z'))
            ->setShip($request->input('ship'))
            ->execute();
    }

    public function cost(Request $request, Cost $cost)
    {
        return $cost
          ->setShip($request->input('ship'))
          ->setAmount($request->input('amount'))
          ->execute();
    }

    public function lazyCalc(Request $request, MakeBattleCalc $calc)
    {
        return $calc
          ->setX($request->input('x'))
          ->setY($request->input('y'))
          ->setZ($request->input('z'))
          ->setDefPlanets($request->input('def'))
          ->setAttPlanets($request->input('att'))
          ->execute();
    }
}
