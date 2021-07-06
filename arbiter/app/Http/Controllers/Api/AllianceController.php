<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

use App\Alliance;
use App\Planet;
use App\AllianceHistory;
use App\Galaxy;
use App\FleetMovement;

class AllianceController extends ApiController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort = $request->input('sort', '-score');
        $page = $request->input('page');
        $perPage = $request->input('perPage', 30);

        $alliances = Alliance::withCount('planets');

        if($sort) {
            $sortDirection = substr($sort, 0, 1);
            $direction = $sortDirection == '+' ? 'ASC' : 'DESC';

            $sort = substr($sort, 1);

            $alliances->orderBy($sort, $direction);
        }

        $alliances = $alliances->paginate($perPage)->appends(['sort' => $sortDirection . $sort, 'perPage' => $perPage]);

        foreach($alliances as $key => $alliance) {
            $alliance->is_alliance = $this->settings['alliance'] == $alliance->id ? true : false;
        }

        return $alliances;
    }

    public function show($id, Request $request)
    {
        if($id) {

            $alliance = Alliance::with(['planets' => function($query) {
                $query->orderBy('x', 'ASC');
                $query->orderBy('y', 'ASC');
                $query->orderBy('z', 'ASC');
            }, 'planets.latestA', 'planets.latestA.au', 'planets.latestA.au.ship', 'planets.latestP', 'planets.latestP.scan', 'planets.latestD', 'planets.latestD.scan'])->where('id', $id)->first();

        } else {
            $alliance = new \stdClass();
            $alliance->id = 0;
            $alliance->planets = Planet::with('outgoing', 'incoming', 'latestA', 'latestA.au', 'latestA.au.ship', 'latestP', 'latestP.scan', 'latestD', 'latestD.scan')
              ->whereNull('alliance_id')
              ->orderBy('x', 'ASC')
              ->orderBy('y', 'ASC')
              ->orderBy('z', 'ASC')->get();
        }

        $alliance->races = $this->getAllianceRaces($alliance->planets);
        $alliance->ships = $this->getAllianceShips($alliance->planets);
        $alliance->tick = $this->tick;

        return $alliance;
    }

    public function update($id, Request $request)
    {
        $alliance = Alliance::find($id);

        $relation = $request->input('relation');
        $nickname = $request->input('nickname');

        $alliance->update([
            'nickname' => $nickname ?? $alliance->nickname,
            'relation' => $relation ?? $alliance->relation
        ]);

        return $alliance;
    }

    public function development($id, Request $request)
    {
        $planets = Planet::with([
            'latestD',
            'latestD.scan'
        ])->where('alliance_id', $id)
        ->get();

        $cons = [
            'fc' => [
                'total' => 0,
                'avg' => 0
            ],
            'mr' => [
                'total' => 0,
                'avg' => 0
            ],
            'cr' => [
                'total' => 0,
                'avg' => 0
            ],
            'er' => [
                'total' => 0,
                'avg' => 0
            ],
            'has_magma' => 0,
            'amps' => [
                'total' => 0,
                'avg' => 0
            ],
            'dists' => [
                'total' => 0,
                'avg' => 0
            ],
            'has_inc' => 0,
            'sc' => [
                'total' => 0,
                'avg' => 0
            ],
            'sd' => [
                'total' => 0,
                'avg' => 0
            ]
        ];

        foreach($planets as $member) {
            if($member->latestD) {
                $cons['fc']['total'] = $cons['fc']['total'] + $member->latestD->finance_centre;
                $cons['mr']['total'] = $cons['mr']['total'] + $member->latestD->metal_refinery;
                $cons['cr']['total'] = $cons['cr']['total'] + $member->latestD->crystal_refinery;
                $cons['er']['total'] = $cons['er']['total'] + $member->latestD->eonium_refinery;
                $cons['amps']['total'] = $cons['amps']['total'] + $member->latestD->wave_amplifier;
                $cons['dists']['total'] = $cons['dists']['total'] + $member->latestD->wave_distorter;
                $cons['sd']['total'] = $cons['sd']['total'] + $member->latestD->structure_defence;
                $cons['sc']['total'] = $cons['sc']['total'] + $member->latestD->security_centre;
                if($member->latestD->waves >= 5) {
                    $cons['has_inc']++;
                }
                if($member->latestD->core >= 4) {
                    $cons['has_magma']++;
                }
            }
        }

        if(count($planets)) {
            $cons['fc']['avg'] = number_format($cons['fc']['total'] / count($planets), 0);
            $cons['mr']['avg'] = number_format($cons['mr']['total'] / count($planets), 0);
            $cons['cr']['avg'] = number_format($cons['cr']['total'] / count($planets), 0);
            $cons['er']['avg'] = number_format($cons['er']['total'] / count($planets), 0);
            $cons['amps']['avg'] = number_format($cons['amps']['total'] / count($planets), 0);
            $cons['dists']['avg'] = number_format($cons['dists']['total'] / count($planets), 0);
            $cons['sd']['avg'] = number_format($cons['sd']['total'] / count($planets), 0);
            $cons['sc']['avg'] = number_format($cons['sc']['total'] / count($planets), 0);
        }

        return $cons;
    }

    private function getAllianceRaces($members) {
        $races = [
            'Ter' => 0,
            'Cat' => 0,
            'Xan' => 0,
            'Zik' => 0,
            'Etd' => 0
        ];

        foreach($members as $member) {
            $races[$member->race]++;
        }

        $chart = [
            ['Race', 'Number'],
            ['Ter', $races['Ter']],
            ['Cat', $races['Cat']],
            ['Xan', $races['Xan']],
            ['Zik', $races['Zik']],
            ['Etd', $races['Etd']]
        ];

        return $chart;
    }

    public function getAllianceShips($members)
    {
        $ships = [];

        foreach($members as $member) {
            if($member->latestA) {
                foreach($member->latestA->au as $ship) {
                    if(isset($ships[$ship->ship->race][$ship->ship->class][$ship->ship->name]['amount'])) {
                        $ships[$ship->ship->race][$ship->ship->class][$ship->ship->name]['amount'] = $ships[$ship->ship->race][$ship->ship->class][$ship->ship->name]['amount'] + $ship->amount;
                        $ships[$ship->ship->race][$ship->ship->class][$ship->ship->name]['value'] = $ships[$ship->ship->race][$ship->ship->class][$ship->ship->name]['value'] + (($ship->ship->metal + $ship->ship->crystal + $ship->ship->eonium) * $ship->amount) / 100;
                    } else {
                        $ships[$ship->ship->race][$ship->ship->class][$ship->ship->name]['amount'] = $ship->amount;
                        $ships[$ship->ship->race][$ship->ship->class][$ship->ship->name]['value'] = (($ship->ship->metal + $ship->ship->crystal + $ship->ship->eonium) * $ship->amount) / 100;
                    }
                }
            }
        }

        $races = ['Terran', 'Cathaar', 'Xandathrii', 'Zikonian', 'Eitraides'];

        $ships = array_merge(array_flip($races), $ships);

        $classes = ['Fighter', 'Corvette', 'Frigate', 'Destroyer', 'Cruiser', 'Battleship'];

        foreach($ships as $key => $race) {
            if(is_array($race)) {
                $ships[$key] = array_merge(array_flip($classes), $race);
            } else {
                unset($ships[$key]);
            }
        }

        $allyValue = 0;

        foreach($classes as $class) {
            $classValue[$class] = 0;
        }

        $items = [];

        foreach($ships as $key => $race) {
            foreach($race as $index => $class) {
                if(!is_array($class)) {
                    unset($ships[$key][$index]);
                } else {
                    foreach($class as $ship => $item) {
                        $items[] = [
                            'race' => $key,
                            'name' => $ship,
                            'amount' => number_format($item['amount'], 0)
                        ];
                        $classValue[$index] = $classValue[$index] + $item['value'];
                        $allyValue = $allyValue + $item['value'];
                    }
                }
            }
        }

        foreach($classValue as $key => $value) {
            unset($classValue[$key]);
            $classValue[$key]['value'] = $value;
            $classValue[$key]['percentage'] = ($value) ? number_format(($value / $allyValue) * 100, 2) : 0;
        }

        $classes = [
            ['Class', 'Amount'],
            ['Fighter', $classValue['Fighter']['value']],
            ['Corvette', $classValue['Corvette']['value']],
            ['Frigate', $classValue['Frigate']['value']],
            ['Destroyer', $classValue['Destroyer']['value']],
            ['Cruiser', $classValue['Cruiser']['value']],
            ['Battleship', $classValue['Battleship']['value']],
        ];

        $arrays = [
            'ships' => array_chunk($items, 2),
            'classes' => $classes
        ];

        return collect($arrays);
    }

    public function history($id, Request $request)
    {
        return AllianceHistory::with('tick')->where('alliance_id', $id)->orderBy('tick', 'DESC')->paginate($request->input('limit', 10));
    }
}
