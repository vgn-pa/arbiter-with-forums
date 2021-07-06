<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Planet;
use App\Tick;
use Config;
use App\Services\Covop\CovopTargets;

class CovOpController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, CovopTargets $covop)
    {
        if(!$this->tick) return;

        return $covop->setHasDev($request->input('has_d', false))
            ->setPopulation($request->input('population', 50))
            ->excludeAlly($this->settings['alliance'])
            ->setAgents($request->input('agents', 0))
            ->setStealth($request->input('stealth', 0))
            ->execute();
    }

    public function store(Request $request)
    {
        $governments = Config::get('governments');

        $secCentres = (int) $request->input('centres', 0);
        $guards = (int) $request->input('guards', 0);
        $roidCount = (int) $request->input('size', 0);
        $population = (int) $request->input('population', 0);
        $government = $request->input('government');
        if(!isset($governments[$government])) $government = 'Nationalism';
        $government = $governments[$government]['alert'];

        $first = (50+5*(min(($guards/($roidCount+1)), 15)));
        $alert = (1+(($secCentres*2.75) + $government + $population)/100);

        $alert = $first * $alert;

        return number_format($alert, 2);
    }

    public function hit($id)
    {
        Planet::find($id)->update(['covop_hit' => 1]);
    }

    public function lastCovopped()
    {
        return Planet::with('latestP', 'latestP.scan', 'latestD', 'latestD.scan', 'alliance')
          ->where('last_covopped', '>', $this->tick-24)->orderBy('last_covopped', 'DESC')->get();
    }
}
