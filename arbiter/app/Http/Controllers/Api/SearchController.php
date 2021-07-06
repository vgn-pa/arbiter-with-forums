<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Auth;
use App\Tick;
use App\Planet;
use App\Galaxy;
use App\Alliance;

class SearchController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        $tick = Tick::orderBy('tick', 'DESC')->first();

        $this->tick = ($tick) ? $tick->tick : 0;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        preg_match("/^(\d+)[.: ](\d+)[.: ](\d+)$/", $search, $planet);
        preg_match("/^(\d+)[.: ](\d+)$/", $search, $galaxy);

        $psearch = ($planet) ? $planet : false;

        // planet search
        if($psearch) {
            $x = $psearch[1];
            $y = $psearch[2];
            $z = $psearch[3];

            $planet = Planet::where([
              'x' => $x,
              'y' => $y,
              'z' => $z
            ])->first();

            if($planet) {
                return ['planet' => $planet->id];
            }
        }

        $gsearch = ($galaxy) ? $galaxy : false;

        // gal search
        if($gsearch) {
            $x = $gsearch[1];
            $y = $gsearch[2];

            $galaxy = Galaxy::where([
              'x' => $x,
              'y' => $y
            ])->first();

            if($galaxy) {
                return ['galaxy' => $galaxy->id];
            }
        }

        // try alliance Search
        $alliance = Alliance::where('name', 'like', '%'.$search.'%')->first();

        if($alliance) {
            return ['alliance' => $alliance->id];
        }

        $planet = Planet::where('nick', 'like', '%'.$search.'%')->first();

        if($planet) {
            return ['planet' => $planet->id];
        }

        return ['alliances' => 0];
    }
}
