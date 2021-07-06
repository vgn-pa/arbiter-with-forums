<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Ship;

class ShipController extends ApiController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ships = Ship::all();

        $items = [];

        foreach($ships as $ship) {
            $items[$ship->race][] = $ship;
        }

        return $items;
    }
}
