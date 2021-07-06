<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Planet;
use App\Galaxy;
use App\Alliance;
use App\GalaxyHistory;
use App\FleetMovement;
use App\PlanetMovement;

class GalaxyController extends ApiController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort = $request->input('sort', '-score');
        $perPage = $request->input('perPage', 30);
        $excludeC200 = $request->input('exclude_c200', null);
        $allianceId = $request->input('alliance_id', null);

        $galaxies = Galaxy::query();

        if($allianceId) {
            $galaxies->with(['planets' => function($q) use($allianceId) {
                $q->where('alliance_id', $allianceId);
            }])->whereHas('planets', function($q) use($allianceId) {
                $q->where('alliance_id', $allianceId);
            })->withCount(['planets as member_count' => function($q) use($allianceId) {
                $q->where('alliance_id', $allianceId);
            }]);
        }

        if($sort) {
            $sortDirection = substr($sort, 0, 1);
            $direction = $sortDirection == '+' ? 'ASC' : 'DESC';

            $sort = substr($sort, 1);

            if($sort == "coords") {
                $galaxies->orderBy('x', $direction);
                $galaxies->orderBy('y', $direction);
            } else {
                $galaxies->orderBy($sort, $direction);
            }
        }

        if($excludeC200) {
            $galaxies->where('x', '!=', '200');
        }

        $galaxies = $galaxies->paginate($perPage)->appends(['sort' => $sortDirection . $sort, 'perPage' => $perPage]);

        foreach($galaxies as $galaxy) {
            $planet50 = $galaxy->planet_count / 2;
            $galaxy->append(['races', 'alliances']);
            $galaxy->is_fort = false;
            if($allianceId) $galaxy->member_size = $galaxy->planets->sum('size');
            foreach($galaxy->alliances as $alliance) {
                if($alliance['count'] > $planet50) $galaxy->is_fort = true;
            }
        }

        return $galaxies;
    }

    public function show($id, Request $request)
    {
        $gals = Galaxy::orderBy('x', 'ASC')->orderBy('y', 'ASC')->get();

        $galaxy = Galaxy::with('planets')->find($id);

        foreach($gals as $key => $gal) {
            if($gal->id == $id) {
                $galaxy->next_gal = isset($gals[$key+1]) ? $gals[$key+1] : null;
                $galaxy->prev_gal = isset($gals[$key-1]) ? $gals[$key-1] : null;
            }
        }

        return $galaxy;
    }

    public function history($id, Request $request)
    {
        return GalaxyHistory::with('tick')->where('galaxy_id', $id)->orderBy('tick', 'DESC')->paginate($request->input('limit', 10));
    }
}
