<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Planet;
use App\PlanetHistory;
use App\Tick;
use App\FleetMovement;
use App\PlanetMovement;
use Config;
use App\Galaxy;

class PlanetController extends ApiController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = $request->input('page');
        $perPage = $request->input('perPage', 50);
        $sort = $request->input('sort', '-score');
        $alliance = $request->input('alliance_id', null);
        $scans = $request->input('scans', null);
        $excludeC200 = $request->input('exclude_c200', null);

        $planets = Planet::with(['alliance' => function($q) {
            $q->select(['id', 'name']);
        }, 'galaxy' => function($q) use($alliance) {
            $q->select(['id', 'rank_score']);
            $q->withCount(['planets' => function($q1) use($alliance) {
                $q1->where('alliance_id', $alliance);
            }]);
        }]);

        if($scans) {
            $planets->with([
                'latestA',
                'latestU',
                'latestJ',
                'latestJ.scan',
                'latestN',
                'latestN.scan',
                'latestP',
                'latestP.scan',
                'latestD',
                'latestD.scan'
            ]);
        }

        if($alliance || $alliance == 0 && !is_null($alliance)) {
            if($alliance) {
                $planets->whereHas('alliance', function($query) use($alliance) {
                    $query->where('alliance_id', $alliance);
                });
            } else {
                $planets->whereNull('alliance_id');
            }
        }

        if($excludeC200) {
            $planets->where('x', '!=', '200');
        }

        if($sort) {
            $sortDirection = substr($sort, 0, 1);
            $direction = $sortDirection == '+' ? 'ASC' : 'DESC';

            $sort = substr($sort, 1);

            if($sort == "coords") {
                $planets->orderBy('x', $direction);
                $planets->orderBy('y', $direction);
                $planets->orderBy('z', $direction);
            } else {
                $planets->orderBy($sort, $direction);
            }
        }

        return $planets->paginate($perPage)->appends(['sort' => $sortDirection . $sort, 'perPage' => $perPage, 'alliance_id' => $alliance]);
    }

    public function show($id, Request $request)
    {
        $planet = Planet::with('alliance', 'latestA', 'latestA.au', 'latestU', 'latestU.u', 'latestJ', 'latestJ.scan', 'latestN', 'latestN.scan', 'latestP', 'latestP.scan', 'latestD', 'latestD.scan')->find($id);

        $planet->income = [];

        $planet = $this->addFleetMovements($planet);

        return $planet;
    }

    public function rankChart($id)
    {
        $history = PlanetHistory::with('tick')->where('planet_id', $id)->orderBy('tick', 'ASC')->get();

        $chart = [[
            'Tick',
            'Size',
            'Value',
            'Score',
            'Xp'
        ]];

        foreach($history as $item) {
            $chart[] = [
                $item->tick,
                $item->rank_size,
                $item->rank_value,
                $item->rank_score,
                $item->rank_xp
            ];
        }

        return $chart;
    }

    private function addFleetMovements($planet)
    {
        $attackedBy = [];
        $attacked = [];
        $defendedBy = [];
        $defended = [];

        foreach($planet->incoming as $incoming) {
            if($incoming->planetFrom->alliance) {
                if($incoming->mission_type == 'Attack') {
                    $attackedBy[$incoming->planetFrom->alliance->id] = $incoming->planetFrom->alliance;
                }
                if($incoming->mission_type == 'Defend' && $incoming->planetTo->galaxy_id != $incoming->planetFrom->galaxy_id) {
                    $defendedBy[$incoming->planetFrom->alliance->id] = $incoming->planetFrom->alliance;
                }
            }
        }

        foreach($planet->outgoing as $outgoing) {
            if($outgoing->planetTo->alliance) {
                if($outgoing->mission_type == 'Attack') {
                    $attacked[$outgoing->planetTo->alliance->id] = $outgoing->planetTo->alliance;
                }
                if($outgoing->mission_type == 'Defend' && $outgoing->planetTo->galaxy_id != $outgoing->planetFrom->galaxy_id) {
                    $defended[$outgoing->planetTo->alliance->id] = $outgoing->planetTo->alliance;
                }
            }
        }

        $planet->attackedBy = implode(', ', collect($attackedBy)->sortBy('name')->pluck('name')->toArray());
        $planet->attacked = implode(', ', collect($attacked)->sortBy('name')->pluck('name')->toArray());
        $planet->defendedBy = implode(', ', collect($defendedBy)->sortBy('name')->pluck('name')->toArray());
        $planet->defended = implode(', ', collect($defended)->sortBy('name')->pluck('name')->toArray());

        return $planet;
    }

    public function history($id, Request $request)
    {
        return PlanetHistory::with('tick')->where('planet_id', $id)->orderBy('tick', 'DESC')->paginate($request->input('limit', 10));

    }

    private function getIncome($planet)
    {
        $governments = Config::get('governments');
        $cores = Config::get('cores');

        $income = [];

        if($planet->latestP) {

            $guardCost = ($planet->latestP->guards != 0) ? str_replace(',', '', $planet->latestP->guards) * 2 : 0;
            $agentCost = ($planet->latestP->agents != 0) ? str_replace(',', '', $planet->latestP->agents) * 5 : 0;

            foreach($income as $resource => $value) {
                foreach($governments as $gov => $values) {
                    $income['metal'][$gov] = 0;
                    $income['crystal'][$gov] = 0;
                    $income['eonium'][$gov] = 0;
                }
            }

            $mRoidIncome = ($planet->latestP->roid_metal) ? $planet->latestP->roid_metal * 250 : 0;
            $cRoidIncome = ($planet->latestP->roid_crystal) ? $planet->latestP->roid_crystal * 250 : 0;
            $eRoidIncome = ($planet->latestP->roid_eonium) ? $planet->latestP->roid_eonium * 250 : 0;

            $mRefIncome = 0;
            $cRefIncome = 0;
            $eRefIncome = 0;

            $coreIncome = 1000;

            $fcPercentage = 0;

            if($planet->latestD) {
                $mRefIncome = ($planet->latestD->metal_refinery) ? $planet->latestD->metal_refinery * 1100 : 0;
                $cRefIncome = ($planet->latestD->crystal_refinery) ? $planet->latestD->crystal_refinery * 1100 : 0;
                $eRefIncome = ($planet->latestD->eonium_refinery) ? $planet->latestD->eonium_refinery * 1100 : 0;

                $coreIncome = $cores[$planet->latestD->core];

                $fcPercentage = ($planet->latestD->finance_centre) ? ($planet->latestD->finance_centre / 2) / 100 : 0;
            }

            foreach($governments as $gov => $details) {
                $bonus = $fcPercentage + $details['mining'] + 0.25;

                $mIncome = $mRoidIncome + $mRefIncome + $coreIncome + 450;
                $cIncome = $cRoidIncome + $cRefIncome + $coreIncome + 277;
                $eIncome = $eRoidIncome + $eRefIncome + $coreIncome + 276;

                //dd(($mIncome * $bonus) * 1.03 - ($mIncome * $bonus));

                $income['metal'][$gov][0] = round(($mIncome * $bonus) - $guardCost - $agentCost);
                $income['metal'][$gov][1] = round((($mIncome * $bonus) * 0.99) - $guardCost - $agentCost);
                $income['metal'][$gov][2] = round((($mIncome * $bonus) * 0.98) - $guardCost - $agentCost);
                $income['metal'][$gov][3] = round((($mIncome * $bonus) * 0.97) - $guardCost - $agentCost);
                $income['metal'][$gov][4] = round((($mIncome * $bonus) * 0.96) - $guardCost - $agentCost);
                $income['metal'][$gov][5] = round((($mIncome * $bonus) * 0.95) - $guardCost - $agentCost);
                $income['crystal'][$gov][0] = round(($cIncome * $bonus) - $guardCost - $agentCost);
                $income['crystal'][$gov][1] = round((($cIncome * $bonus) * 0.99) - $guardCost - $agentCost);
                $income['crystal'][$gov][2] = round((($cIncome * $bonus) * 0.98) - $guardCost - $agentCost);
                $income['crystal'][$gov][3] = round((($cIncome * $bonus) * 0.97) - $guardCost - $agentCost);
                $income['crystal'][$gov][4] = round((($cIncome * $bonus) * 0.96) - $guardCost - $agentCost);
                $income['crystal'][$gov][5] = round((($cIncome * $bonus) * 0.95) - $guardCost - $agentCost);
                $income['eonium'][$gov][0] = round(($eIncome * $bonus) - $guardCost - $agentCost);
                $income['eonium'][$gov][1] = round((($eIncome * $bonus) * 0.99) - $guardCost - $agentCost);
                $income['eonium'][$gov][2] = round((($eIncome * $bonus) * 0.98) - $guardCost - $agentCost);
                $income['eonium'][$gov][3] = round((($eIncome * $bonus) * 0.97) - $guardCost - $agentCost);
                $income['eonium'][$gov][4] = round((($eIncome * $bonus) * 0.96) - $guardCost - $agentCost);
                $income['eonium'][$gov][5] = round((($eIncome * $bonus) * 0.95) - $guardCost - $agentCost);

                $income['total'][$gov][0] = [
                    'resources' => $income['metal'][$gov][0] + $income['crystal'][$gov][0] + $income['eonium'][$gov][0],
                    'value' => round($income['metal'][$gov][0]/150) + round($income['crystal'][$gov][0]/150) + round($income['eonium'][$gov][0]/150)
                ];
                $income['total'][$gov][1] = [
                    'resources' => $income['metal'][$gov][1] + $income['crystal'][$gov][1] + $income['eonium'][$gov][1],
                    'value' => round($income['metal'][$gov][1]/150) + round($income['crystal'][$gov][1]/150) + round($income['eonium'][$gov][1]/150)
                ];
                $income['total'][$gov][2] = [
                    'resources' => $income['metal'][$gov][2] + $income['crystal'][$gov][2] + $income['eonium'][$gov][2],
                    'value' => round($income['metal'][$gov][2]/150) + round($income['crystal'][$gov][2]/150) + round($income['eonium'][$gov][2]/150)
                ];
                $income['total'][$gov][3] = [
                    'resources' => $income['metal'][$gov][3] + $income['crystal'][$gov][3] + $income['eonium'][$gov][3],
                    'value' => round($income['metal'][$gov][3]/150) + round($income['crystal'][$gov][3]/150) + round($income['eonium'][$gov][3]/150)
                ];
                $income['total'][$gov][4] = [
                    'resources' => $income['metal'][$gov][4] + $income['crystal'][$gov][4] + $income['eonium'][$gov][4],
                    'value' => round($income['metal'][$gov][4]/150) + round($income['crystal'][$gov][4]/150) + round($income['eonium'][$gov][4]/150)
                ];
                $income['total'][$gov][5] = [
                    'resources' => $income['metal'][$gov][5] + $income['crystal'][$gov][5] + $income['eonium'][$gov][5],
                    'value' => round($income['metal'][$gov][5]/150) + round($income['crystal'][$gov][5]/150) + round($income['eonium'][$gov][5]/150)
                ];
            }

        }

        return $income;
    }

    public function ships($id)
    {
        $planet = Planet::with('latestA', 'latestA.au')->find($id);

        $ships = [];

        $totalValue = 0;

        if($planet->latestA) {
            foreach($planet->latestA->au as $ship) {
                $value = (($ship->ship->metal + $ship->ship->crystal + $ship->ship->eonium) * $ship->amount) / 100;
                $ships[] = [
                    'name' => $ship->ship->name,
                    'amount' => number_format($ship->amount, 0),
                    'value' => $value
                ];
                $totalValue = $totalValue + $value;
            }
        }

        foreach($ships as $key => $ship) {
            $ships[$key]['percentage'] = number_format(($ship['value'] / $totalValue) * 100, 2);
        }

        return collect([
            'ships' => $ships,
            'age' => ($planet->latestA) ? $this->tick-$planet->latestA->tick : 0
        ]);
    }

    public function movements(Request $request)
    {
        $movements = PlanetMovement::with([
            'planet' => function($q) {
                $q->select(['id', 'x', 'y', 'z', 'deleted_at', 'galaxy_id', 'age', 'ruler_name', 'planet_name', 'race', 'size', 'score', 'value', 'alliance_id', 'nick', 'xp'])->withTrashed();
            },
            'planet.alliance' => function($q) {
                $q->select(['id', 'name']);
            }
        ])->orderBy('tick', 'DESC')->orderBy('id', 'ASC');

        if($request->input('planet_id')) {
            $movements = $movements->where('planet_id', $request->input('planet_id'));
        }

        if($request->input('galaxy_id')) {
            $galaxy = Galaxy::find($request->input('galaxy_id'));

            $movements = $movements->whereRaw('(from_x = ? AND from_y = ?) OR (to_x = ? AND to_y = ?)', [$galaxy->x, $galaxy->y, $galaxy->x, $galaxy->y]);
        }

        return $movements->paginate($request->input('limit', 10))->appends(['galaxy_id' => $request->input('galaxy_id', null)]);
    }
}
