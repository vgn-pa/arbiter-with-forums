<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\AttackRequest;

use App\Http\Controllers\Controller;
use App\Attack;
use App\PlanetHistory;
use App\AttackTarget;
use App\AttackBooking;
use Auth;
use App\Tick;
use App\User;
use App\Galaxy;
use App\Planet;
use App\Services\ParseCoordsList;
use App\Services\Misc\MakeBattleCalc;
use App;
use App\Services\MyBookings;
use App\Alliance;

class AttackController extends ApiController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attacks = Attack::with([
            'targets' => function($query) {
                $query->withCount(['bookings as claimed_count' => function($q2) {
                    $q2->whereNotNull('user_id');
                }]);
            },
            'targets.bookings'
        ])
        ->withCount('bookings')
        ->withCount('targets')
        ->where(['is_closed' => 0, 'is_opened' => 1])
        ->orderBy('land_tick', 'DESC')
        ->get();

        foreach($attacks as $attack) {
            $attack->claimed_count = 0;
            foreach($attack->targets as $target) {
                $attack->claimed_count = $attack->claimed_count+$target->claimed_count;
            }
            $attack->claimed_percentage = ($attack->bookings_count) ? number_format(($attack->claimed_count/$attack->bookings_count)*100, 0) : 0;
        }

        return $attacks;
    }

    public function store(AttackRequest $request, ParseCoordsList $coordParser)
    {
        $waves = $request->input('waves');
        $landTick = $request->input('land_tick');
        $scheduleTime = $request->input('time');
        $targets = $request->input('targets');

        $attack = Attack::create([
            'land_tick' => $landTick,
            'waves' => $waves,
            'notes' => $request->input('notes'),
            'is_opened' => (!$scheduleTime) ? true : false,
            'scheduled_time' => $request->input('time'),
            'attack_id' => 'temp'
        ]);

        $attack->attack_id = md5(uniqid());
        $attack->save();

        $targets = $coordParser->setCoords($targets)->execute();

        $this->addTargets($attack, $targets, $landTick, $waves);
        $this->reorderTargets($attack->id);

        return $this->index();
    }

    private function reorderTargets($id)
    {
        $targets = AttackTarget::where('attack_id', $id)->get();

        foreach($targets as $target) {
            $target->x = $target->planet->x;
            $target->y = $target->planet->y;
            $target->z = $target->planet->z;
        }

        $targets = array_orderby($targets->toArray(), 'x', SORT_ASC, 'y', SORT_ASC, 'z', SORT_ASC);

        $int = 1;

        foreach($targets as $target) {
            AttackTarget::where('id', $target['id'])->update(['order' => $int++]);
        }
    }

    private function addTargets($attack, $targets, $landTick, $waves)
    {
        $attackTargets = [];

        foreach($targets as $target) {

            $lt = $landTick;
            $planet = Planet::where(['x' => $target['x'], 'y' => $target['y'], 'z' => $target['z']])->first();

            if($planet) {
                // check if planet already exists in this attack
                if(!$target = AttackTarget::where(['attack_id' => $attack->id, 'planet_id' => $planet->planet_id])->first()) {
                    $attackTarget = AttackTarget::create([
                        'attack_id' => $attack->id,
                        'planet_id' => $planet->id
                    ]);

                    for($x = $waves; $x > 0; $x--) {

                        AttackBooking::create([
                            'attack_id' => $attack->id,
                            'attack_target_id' => $attackTarget->id,
                            'land_tick' => $lt
                        ]);

                        $lt++;
                    }
                }
            }
        }
    }

    public function show($id, Request $request)
    {
        $user = User::with('planet')->find(Auth::user()->id);

        if(!$user->planet) return response()->json(['error' => "You must set your co-ords to access attacks."], 422); 

        $sort = $request->input('sort', '+coords');

        $tick = Tick::orderBy('tick')->first();

        $attack = Attack::with([
            'targets' => function($q) {
                $q->orderBy('order', 'ASC');
            },
            'targets.bookings',
            'targets.bookings.user' => function($q) {
                $q->select(['id', 'name']);
            },
            'targets.planet' => function($q) {
                $q->select(['id', 'galaxy_id', 'x', 'y', 'z', 'value', 'race', 'score', 'size', 'amps', 'waves', 'nick', 'alliance_id', 'latest_p', 'latest_d', 'latest_u', 'latest_au', 'latest_j', 'growth_percentage_size', 'growth_percentage_value', 'growth_percentage_score']);
            },
            'targets.planet.latestP',
            'targets.planet.latestP.scan',
            'targets.planet.latestD',
            'targets.planet.latestD.scan',
            'targets.planet.latestJ',
            'targets.planet.latestJ.scan',
            'targets.planet.latestU',
            'targets.planet.latestU.u',
            'targets.planet.latestU.u.ship',
            'targets.planet.latestA',
            'targets.planet.latestA.au',
            'targets.planet.latestA.au.ship',
            'targets.planet.alliance' => function($q) {
                $q->select(['id', 'name']);
            }
        ])->where('attack_id', $id)->first();

        $alliances = [];
        $bookings = [];

        foreach($attack->targets as $target) {

            if($this->hasRole('Admin') || $this->hasRole('BC')) {
                if($target->planet->alliance) {
                    if(!isset($alliances[$target->planet->alliance->name])) $alliances[$target->planet->alliance->name] = 0;
                    $alliances[$target->planet->alliance->name]++;
                }

                foreach($target->bookings as $booking) {
                    if($booking->user) {
                        if(!isset($bookings[$booking->user->name])) $bookings[$booking->user->name] = 0;
                        $bookings[$booking->user->name]++;
                    }
                }
            }

            $target->showShips = false;

            $anti = [
                'Fighter' => 'na',
                'Corvette' => 'na',
                'Frigate' => 'na',
                'Destroyer' => 'na',
                'Cruiser' => 'na',
                'Battleship' => 'na'
            ];

            $scan = null;

            $calc = App::make(MakeBattleCalc::class);
            $target->calc = $calc->setX($target->planet->x)
              ->setY($target->planet->y)
              ->setZ($target->planet->z)
              ->execute();

            if($target->planet->latestA && $target->planet->latestA->tick >= ($tick->tick-24)) { 
                $scan = 'latestA'; $deet = "au"; 
            }
            if(((!$target->planet->latestA || $target->planet->latestA->tick < ($tick->tick-24)) && $target->planet->latestU && $target->planet->latestU->tick >= ($tick->tick-24))) { 
                $scan = 'latestU'; $deet = "u"; 
            }

            if($scan) {
                foreach($target->planet->{$scan}->{$deet} as $ship) {
                    foreach($anti as $class => $value) {
                        if($ship->ship->t1 == $class) { $anti[$class] = true; }
                        if($ship->ship->t2 == $class) { $anti[$class] = true; }
                        if($ship->ship->t3 == $class) { $anti[$class] = true; }
                    }
                }

                foreach($anti as $key => $value) {
                    if($value === 'na') { $anti[$key] = false; }
                }
            }

            if(!empty($target->planet->latestP) && $target->planet->latestP->scan->tick >= ($tick->tick-24)) {
                $metal = str_replace(',','', $target->planet->latestP->res_metal);
                $crystal = str_replace(',','', $target->planet->latestP->res_crystal);
                $eonium = str_replace(',','', $target->planet->latestP->res_eonium);
                $resources = $metal + $crystal + $eonium;
                $target->planet->latestP->stock = number_shorten($resources, 1);
                $target->planet->latestP->prod_res = number_shorten(str_replace(',','',$target->planet->latestP->prod_res), 1);
            }

            $target->anti = $anti;

            $wave = 1;

            $target->cap_xp = "";
            $target->cap_roids = "";
        }

        $attack = $attack->toArray();

        $galX = $user->planet->x;
        $galY = $user->planet->y;

        foreach($attack['targets'] as $key => $target) {
            $attack['targets'][$key]['x'] = $target['planet']['x'];
            $attack['targets'][$key]['y'] = $target['planet']['y'];
            $attack['targets'][$key]['z'] = $target['planet']['z'];
            $attack['targets'][$key]['size'] = $target['planet']['size'];
            $attack['targets'][$key]['amps'] = $target['planet']['amps'];
            $attack['targets'][$key]['value'] = $target['planet']['value'];
            $attack['targets'][$key]['score'] = $target['planet']['score'];

            // Hide own gal mates
            if((!$this->hasRole('Admin') && !$this->hasRole('BC')) && $target['planet']['x'] == $galX && $target['planet']['y'] == $galY) {
                unset($attack['targets'][$key]);
            }
        }

        if($this->hasRole('Admin') || $this->hasRole('BC')) {

            $books = [];

            uasort($bookings, function($a, $b) {
                return $a < $b;
            });

            foreach($bookings as $nick => $count) {
                $books[] = [
                    'nick' => $nick,
                    'count' => $count
                ];
            }
            

            $attack['bookings'] = $books;

            $attack['alliances'] = $this->allianceCounts($alliances);

        }

        if($sort) {
            $sortSymbol = substr($sort, 0, 1);
            $direction = $sortSymbol == '+' ? 'ASC' : 'DESC';

            $sort = substr($sort, 1);

            if($sort == "coords") {
                switch($sortSymbol) {
                    case "+": $attack['targets'] = array_orderby($attack['targets'], 'x', SORT_ASC, 'y', SORT_ASC, 'z', SORT_ASC);
                      break;
                    case "-": $attack['targets'] = array_orderby($attack['targets'], 'x', SORT_DESC, 'y', SORT_DESC, 'z', SORT_DESC);
                      break;
                }
            } else {
                usort($attack['targets'], function($a, $b) use($sortSymbol, $sort) {
                    switch($sortSymbol) {
                        case "+": $return = $a[$sort] > $b[$sort];
                          break;
                        case "-": $return = $a[$sort] < $b[$sort];
                          break;
                    }
                    return $return;
                });
            }
        }

        $attack['current_tick'] = $this->tick;

        return collect($attack);
    }

    private function allianceCounts($alliances)
    {
        $allianceCounts = [];

        $allies = Alliance::all()->keyBy('name')->toArray();

        uasort($alliances, function($a, $b) {
            return $a < $b;
        });

        foreach($alliances as $alliance => $count) {
            $allianceCounts[] = [
                'name' => $alliance,
                'nickname' => $allies[$alliance]['nickname'] ?? str_replace(" ", "", substr($alliance, 0, 3)),
                'count' => $count
            ];
        }

        return $allianceCounts;
    }

    public function update($id, Request $request, ParseCoordsList $coordParser)
    {
        $attack = Attack::with('targets', 'targets.bookings')->where('attack_id', $id)->first();

        $lt = $request->input('land_tick');

        // lt change
        if($lt != $attack->land_tick) {
            $diff = $lt - $attack->land_tick;

            $attack->land_tick = $lt;

            foreach($attack->targets as $target) {
                foreach($target->bookings as $booking) {
                    $booking->land_tick = $booking->land_tick + $diff;
                    $booking->save();
                }
            }
        }

        $attack->notes = $request->input('notes');
        $attack->save();

        if($targets = $request->input('newTargets')) {
            $targets = $coordParser->setCoords($targets)->execute();

            $this->addTargets($attack, $targets, $attack->land_tick, $attack->waves);
            $this->reorderTargets($attack->id);
        }
    }

    public function scheduled()
    {
        return Attack::where(['is_closed' => 0, 'is_opened' => 0])->get();
    }

    private function calcXp($cap, $targetValue, $targetScore)
    {
        $user = User::with('planet', 'planet.currentPlanet')->where('id', Auth::user()->id)->first();

        $value = $user->planet->getOriginal('value');
        $score = $user->planet->getOriginal('score');

        $mc = (Auth::user()->military_centres / 200) + 1;

        $bravery = max(0.2,(min(2.2,$targetScore/$score) -0.2)) * max(0.2,(min(1.8,$targetValue/$value) - 0.1))/((6+max(4,$score/$value))/10);

        //dd($cap, $bravery, $mc);

        $xp = $cap * 10 * $bravery * $mc;

        return number_shorten($xp, 1);
    }

    private function calcCap($size, $wave, $targetValue)
    {
        $user = User::with('planet', 'planet.currentPlanet')->where('id', Auth::user()->id)->first();

        $value = $user->planet->getOriginal('value');
        $score = $user->planet->getOriginal('score');

        for($int = $wave; $int > 0; $int--) {
            $cap = (0.25 * pow(($targetValue / $value),0.5))*$size;
            $size = $size - $cap;
        }

        return (int) $cap;
    }

    public function book($attackId, $bookingId, Request $request, MyBookings $bookings)
    {
        if($attack = Attack::where(['attack_id' => $attackId, 'is_opened' => 1, 'is_closed' => 0])->first()) {
            $booking = AttackBooking::where('id', $bookingId)->whereNull('user_id')->update([
                'user_id' => Auth::user()->id
            ]);

            if(!$booking) {
                return response()->json(['error' => 'Target already booked'], 422);
            }
        }

        return $bookings
                ->setUserId(Auth::user()->id)
                ->setAttackId($attackId)
                ->execute();
    }

    public function close($id)
    {
        Attack::where('id', $id)->update([
            'is_closed' => 1
        ]);

        return $this->index();
    }

    public function deleteTarget($attackId, $targetId)
    {
        AttackTarget::where('id', $targetId)->delete();
        AttackBooking::where('attack_target_id', $targetId)->delete();
    }

    public function addWaves($id)
    {
        $attack = Attack::with('targets')->where('attack_id', $id)->first();

        $attack->waves = $attack->waves+1;
        $attack->save();

        $landTick = $attack->land_tick;
        $waves = $attack->waves;

        $newWave = $landTick+($waves-1);

        foreach($attack->targets as $target) {
            AttackBooking::create([
                'attack_id' => $attack->id,
                'attack_target_id' => $target->id,
                'land_tick' => $newWave
            ]);
        }
    }

    public function removeWaves($id)
    {
        $attack = Attack::with('targets')->where('attack_id', $id)->first();

        $attack->waves = $attack->waves-1;
        $attack->save();

        $landTick = $attack->land_tick;
        $waves = $attack->waves;

        $deleteWave = $landTick+($waves);

        AttackBooking::where([
            'land_tick' => $deleteWave,
            'attack_id' => $attack->id
        ])->delete();
    }

    public function closed()
    {
        $attacks = Attack::with([
            'targets' => function($query) {
                $query->withCount(['bookings as claimed_count' => function($q2) {
                    $q2->whereNotNull('user_id');
                }]);
            },
            'targets.bookings',
            'targets.planet' => function($query) {
                $query->select(['id', 'alliance_id']);
            },
            'targets.planet.alliance' => function($query) {
                $query->select(['id', 'name']);
            }
        ])
        ->withCount('bookings')
        ->withCount('targets')
        ->where(['is_closed' => 1, 'is_opened' => 1])
        ->orderBy('created_at', 'DESC')
        ->get();

        if($this->hasRole('Admin') || $this->hasRole('BC')) {
            
        }
        foreach($attacks as $attack) {
            $attack->claimed_count = 0;
            $alliances = [];
            $claims = [];
            
            foreach($attack->targets as $target) {
                
                $attack->claimed_count = $attack->claimed_count+$target->claimed_count;

                // Count alliance targets
                if(isset($target->planet->alliance)) {
                    if(!isset($alliances[$target->planet->alliance->name])) $alliances[$target->planet->alliance->name] = 0;
                    if(!isset($claims[$target->planet->alliance->name])) $claims[$target->planet->alliance->name] = 0;
                    $alliances[$target->planet->alliance->name]++;
                    $claims[$target->planet->alliance->name] = $claims[$target->planet->alliance->name]+$target->claimed_count;
                }
            }
            
            $attack['alliances'] = $this->allianceCounts($alliances);
            $attack['claims'] = $this->allianceCounts($claims);
            $attack->claimed_percentage = ($attack->bookings_count) ? number_format(($attack->claimed_count/$attack->bookings_count)*100, 0) : 0;
        }

        return $attacks;
    }
}
