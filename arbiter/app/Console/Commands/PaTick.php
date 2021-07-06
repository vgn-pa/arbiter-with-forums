<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Planet;
use App\PlanetHistory;
use App\Galaxy;
use App\GalaxyHistory;
use App\Alliance;
use App\AllianceHistory;
use App\Tick;
use App\FleetMovement;
use App\PlanetMovement;
use Carbon\Carbon;
use Config;
use DB;
use Exception;
use Log;
use Longman\TelegramBot\Request;
use App\Setting;
use Longman\TelegramBot\Telegram;

class PaTick extends Command
{
    private $start;
    private $planetStart;
    private $galStart;
    private $allyStart;
    private $tick;
    private $hour;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pa:tick';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
    * Execute the console command.
    *
    * @return mixed
    */
    public function handle()
    {
        $this->hour = Carbon::now()->hour;

        $this->start = microtime(true);
        $this->tickAlliances();
        $this->tickGalaxies();
        $this->tickPlanets();
        if($this->tick) {
            Tick::where('tick', $this->tick)->update([
               'length' => number_format(microtime(true) - $this->start, 2)
            ]);
            $this->call('pa:checkIntel');
            $this->call('pa:calculateBattlegroups');

            return $this->sendTGNotification();
        }
    }

    private function sendTGNotification()
    {
        $chatId = Setting::where('name', 'tg_notifications_channel')->first();

        if($chatId) {
            $data = [
                'chat_id' => $chatId->value,
                'text'    => "Tick " . $this->tick,
            ];

            $telegram = new Telegram(Config::get('phptelegrambot.bot.api_key'), Config::get('phptelegrambot.bot.name'));
            return Request::sendMessage($data);
        }
    }

    private function tickPlanets()
    {
        $this->planetStart = microtime(true);
        
        try {
            $planets = file_get_contents(Config::get('dumps.planets'));
        } catch(Exception $e) {
            return;
        }

        $dump = explode("\n", $planets);

       $tick = str_replace('Tick: ', '', $dump[3]);

       if(!PlanetHistory::where('tick', $tick)->first()) {

           // Reset covert op hits
           Planet::whereNotNull('id')->update(['covop_hit' => 0]);

           $planets = array_slice($dump, 8);

           $currentPlanets = Planet::all()->keyBy('planet_id');
           $currentGalaxies = Galaxy::all();

           $gals = [];

           foreach($currentGalaxies as $galaxy) {
              $gals[$galaxy->x . ":" . $galaxy->y] = $galaxy;
           }

           foreach($planets as $planet) {

               $planet = explode("\t", $planet);

               if(count($planet) > 2) {

                   $plan = isset($currentPlanets[$planet[0]]) ? $currentPlanets[$planet[0]] : null;

                   $id = $planet[0];
                   $x = $planet[1];
                   $y = $planet[2];
                   $z = $planet[3];
                   $pname = utf8_encode(trim($planet[4], '"'));
                   $rname = utf8_encode(trim($planet[5], '"'));
                   $race = $planet[6];
                   $size = $planet[7];
                   $score = $planet[8];
                   $value = $planet[9];
                   $xp = $planet[10];

                   $galaxy = isset($gals[$x . ":" . $y]) ? $gals[$x . ":" . $y] : null;

                   // If there's no existing planet, create one
                   if(empty($plan)) {
                     $plan = new Planet();
                     $plan->planet_id = $id;
                     $plan->age = 0;
                   }

                   $valueChange = 0;
                   $scoreChange = 0;
                   $xpChange = 0;
                   $sizeChange = 0;

                   if(!empty($plan)) {
                      $valueChange = $value - $plan->value;
                      $scoreChange = $score - $plan->score;
                      $xpChange = $xp - $plan->xp;
                      $sizeChange = $size - $plan->size;
                   }

                   $plan->tick_roids = $plan->tick_roids + $size;
                   if(is_numeric($plan->size) && $size > $plan->size) {
                        $plan->round_roids = $plan->round_roids + $sizeChange;
                        $plan->ticks_roiding = $plan->ticks_roiding+1;
                   }
                   if(is_numeric($plan->size) && $size < $plan->size) {
                      $plan->lost_roids = $plan->lost_roids - $sizeChange;
                      $plan->ticks_roided = $plan->ticks_roided+1;
                   }
                   if($plan->round_roids == 0) $plan->round_roids = $size;

                   $currentX = $plan->x;
                   $currentY = $plan->y;
                   $currentZ = $plan->z;
                   $plan->x = $x;
                   $plan->y = $y;
                   $plan->z = $z;
                   $plan->planet_name = $pname;
                   $plan->ruler_name = $rname;
                   $plan->race = $race;
                   $plan->size = $size;
                   $plan->score = $score;
                   $plan->value = $value;
                   $plan->xp = $xp;
                   $plan->galaxy_id = $galaxy->id;
                   $plan->tick = $tick;
                   $plan->age = $plan->age+1;
                   if($this->hour == 0) {
                      $plan->day_value = $value;
                      $plan->day_score = $score;
                      $plan->day_size = $size;
                      $plan->day_xp = $xp;
                   }

                   if($sizeChange == -3 || $sizeChange == -6) {
                      $plan->last_covopped = $tick-1;
                   }

                   $plan->save();

                   if($currentX != $x || $currentY != $y || $currentZ != $z) {
                        // Track planet movement
                        PlanetMovement::create([
                            'planet_id' => $plan->id,
                            'from_x' => $currentX,
                            'from_y' => $currentY,
                            'from_z' => $currentZ,
                            'to_x' => $x,
                            'to_y' => $y,
                            'to_z' => $z,
                            'tick' => $tick
                        ]);
                   }

                   $ph[$plan->id] = [
                       'planet_id'    => $plan->id,
                       'x'            => $x,
                       'y'            => $y,
                       'z'            => $z,
                       'planet_name'  => $pname,
                       'ruler_name'   => $rname,
                       'race'         => $race,
                       'size'         => $size,
                       'score'        => $score,
                       'value'        => $value,
                       'xp'           => $xp,
                       'tick'         => $tick,
                       'change_value' => $valueChange,
                       'change_score' => $scoreChange,
                       'change_xp'    => $xpChange,
                       'change_size'  => $sizeChange
                   ];
               }
           }

           try {
              $this->updatePlanetRanks($tick, $ph);
           }
           catch (Exception $e) {
              Log::error($e->getMessage());
           }

           try {
             $this->updatePlanetGrowth();
           }
           catch (Exception $e) {
              Log::error($e->getMessage());
           }

           $ids = PlanetHistory::where('tick', $tick)->get()->pluck('planet_id');

           if(count($ids)) {
              $toDelete = Planet::whereNotIn('id', $ids)->get()->pluck('id');
              if(count($toDelete)) {
                 Planet::whereIn('id', $toDelete)->delete();
                 FleetMovement::whereIn('planet_from_id', $toDelete)->delete();
                 FleetMovement::whereIn('planet_to_id', $toDelete)->delete();
              }
           }

           Tick::where('tick', $tick)->update([
              'planet_time' => number_format(microtime(true) - $this->planetStart, 2)
           ]);
       }
    }

    private function updatePlanetGrowth()
    {
        $planets = Planet::all();

        foreach($planets as $planet) {
            $score = $planet->getOriginal('score');
            $value = $planet->getOriginal('value');
            $size = $planet->getOriginal('size');
            $xp = $planet->getOriginal('xp');
            $rank_score = $planet->rank_score;
            $rank_value = $planet->rank_value;
            $rank_size = $planet->rank_size;
            $rank_xp = $planet->rank_xp;

            $planet->growth_score = $score - $planet->day_score;
            $planet->growth_value = $value - $planet->day_value;
            $planet->growth_size = $size - $planet->day_size;
            $planet->growth_xp = $xp - $planet->day_xp;
            $planet->growth_rank_score =  $planet->day_rank_score - $rank_score;
            $planet->growth_rank_value =  $planet->day_rank_value - $rank_value;
            $planet->growth_rank_size =  $planet->day_rank_size - $rank_size;
            $planet->growth_rank_xp =  $planet->day_rank_xp - $rank_xp;

            $scoreMod = (strstr($planet->growth_score, "-")) ? "-" : "";
            $valueMod = (strstr($planet->growth_value, "-")) ? "-" : "";
            $sizeMod = (strstr($planet->growth_size, "-")) ? "-" : "";
            $xpMod = (strstr($planet->growth_xp, "-")) ? "-" : "";

            $planet->growth_percentage_score = ($planet->day_score) ? $scoreMod . abs($planet->growth_score) / $planet->day_score * 100 : 0;
            $planet->growth_percentage_value = ($planet->day_value) ? $valueMod . abs($planet->growth_value) / $planet->day_value * 100 : 0;
            $planet->growth_percentage_size = ($planet->day_size) ? $sizeMod . abs($planet->growth_size) / $planet->day_size * 100 : 0;
            $planet->growth_percentage_xp = ($planet->day_xp) ? $xpMod . abs($planet->growth_xp) / $planet->day_xp * 100 : 0;

            $planet->save();
        }
    }

    private function updatePlanetRanks($tick, $ph)
    {
        $valPlanets = Planet::orderBy('value', 'DESC')->get()->keyBy('id');
        $scorePlanets = Planet::orderBy('score', 'DESC')->get()->keyBy('id');
        $sizePlanets = Planet::orderBy('size', 'DESC')->get()->keyBy('id');
        $xpPlanets = Planet::orderBy('xp', 'DESC')->get()->keyBy('id');
        $roundRoids = Planet::orderBy('round_roids', 'DESC')->get()->keyBy('id');
        $lostRoids = Planet::orderBy('lost_roids', 'DESC')->get()->keyBy('id');
        $planets = Planet::all()->keyBy('id');

        $valRank = 1;

        foreach($valPlanets as $planetId => $planet) {
            $planets[$planetId]->rank_value = $valRank;
            if($this->hour == 0) $planets[$planetId]->day_rank_value = $valRank;
            $valRank++;
        }

        $scoreRank = 1;

        foreach($scorePlanets as $planetId => $planet) {
            $planets[$planetId]->rank_score = $scoreRank;
            if($this->hour == 0) $planets[$planetId]->day_rank_score = $scoreRank;
            $scoreRank++;
        }

        $sizeRank = 1;

        foreach($sizePlanets as $planetId => $planet) {
            $planets[$planetId]->rank_size = $sizeRank;
            if($this->hour == 0) $planets[$planetId]->day_rank_size = $sizeRank;
            $sizeRank++;
        }

        $xpRank = 1;

        foreach($xpPlanets as $planetId => $planet) {
            $planets[$planetId]->rank_xp = $xpRank;
            if($this->hour == 0) $planets[$planetId]->day_rank_xp = $xpRank;
            $xpRank++;
        }

        $roundRoidsRank = 1;

        foreach($roundRoids as $planetId => $planet) {
            $planets[$planetId]->rank_round_roids = $roundRoidsRank;
            if($this->hour == 0) $planets[$planetId]->day_rank_round_roids = $roundRoidsRank;
            $roundRoidsRank++;
        }

        $lostRoidsRank = 1;

        foreach($lostRoids as $planetId => $planet) {
            $planets[$planetId]->rank_lost_roids = $lostRoidsRank;
            if($this->hour == 0) $planets[$planetId]->day_rank_lost_roids = $lostRoidsRank;
            $lostRoidsRank++;
        }

        foreach($planets as $planetId => $planet) {
            $planet->save();
            if(isset($ph[$planetId])) {
                $ph[$planetId]['rank_value'] = $planet->rank_value;
                $ph[$planetId]['rank_score'] = $planet->rank_score;
                $ph[$planetId]['rank_xp'] = $planet->rank_xp;
                $ph[$planetId]['rank_size'] = $planet->rank_size;
            }
        }

        PlanetHistory::insert($ph);
    }

    private function tickGalaxies()
    {
        $this->galStart = microtime(true);

        try {
            $galaxies = file_get_contents(Config::get('dumps.galaxies'));
        } catch(Exception $e) {
            return;
        }

       $dump = explode("\n", $galaxies);

       $tick = str_replace('Tick: ', '', $dump[3]);

       $currentGalaxies = Galaxy::withCount('planets')->get();

       $currentGals = [];

       foreach($currentGalaxies as $galaxy) {
          $currentGals[$galaxy->x . ":" . $galaxy->y] = $galaxy;
       }

       if(!GalaxyHistory::where('tick', $tick)->first()) {

           $galaxies = array_slice($dump, 8);

           foreach($galaxies as $galaxy) {

               $galaxy = explode("\t", $galaxy);

               if(count($galaxy) > 2) {

                   $existingGalaxy = isset($currentGals[$galaxy[0] . ":" . $galaxy[1]]) ? $currentGals[$galaxy[0] . ":" . $galaxy[1]] : null;

                   $x = $galaxy[0];
                   $y = $galaxy[1];
                   $name = utf8_encode(trim($galaxy[2], '"'));
                   $size = $galaxy[3];
                   $score = $galaxy[4];
                   $value = $galaxy[5];
                   $xp = $galaxy[6];

                   if(!$existingGalaxy) {
                      $existingGalaxy = new Galaxy();
                      $existingGalaxy->x = $x;
                      $existingGalaxy->y = $y;
                      $existingGalaxy->planet_count = Planet::where(['x' => $x, 'y' => $y])->count();
                   }

                   $valueChange = 0;
                   $scoreChange = 0;
                   $xpChange = 0;
                   $sizeChange = 0;

                   if(!empty($existingGalaxy)) {
                      $valueChange = $value - $existingGalaxy->value;
                      $scoreChange = $score - $existingGalaxy->score;
                      $xpChange = $xp - $existingGalaxy->xp;
                      $sizeChange = $size - $existingGalaxy->size;
                      $existingPlanetCount = $existingGalaxy->planet_count;
                   }

                   $existingGalaxy->size = $size;
                   $existingGalaxy->score = $score;
                   $existingGalaxy->value = $value;
                   $existingGalaxy->xp = $xp;
                   $existingGalaxy->name = $name;
                   $existingGalaxy->ratio = number_format(10000 * ($size / $value), 2);
                   $existingGalaxy->planet_count = ($existingGalaxy->planets_count) ? ($existingGalaxy->planets_count) : $existingGalaxy->planet_count;
                   if($this->hour == 0) {
                      $existingGalaxy->day_value = $value;
                      $existingGalaxy->day_score = $score;
                      $existingGalaxy->day_size = $size;
                      $existingGalaxy->day_xp = $xp;
                      $existingGalaxy->day_planet_count = $existingGalaxy->planet_count;
                   }
                   $existingGalaxy->save();

                   $gh[$existingGalaxy->id] = [
                       'x'                    => $x,
                       'y'                    => $y,
                       'galaxy_name'          => $name,
                       'size'                 => $size,
                       'score'                => $score,
                       'value'                => $value,
                       'xp'                   => $xp,
                       'tick'                 => $tick,
                       'planet_count'         => $existingGalaxy->planet_count,
                       'galaxy_id'            => $existingGalaxy->id,
                       'change_value'         => $valueChange,
                       'change_score'         => $scoreChange,
                       'change_xp'            => $xpChange,
                       'change_size'          => $sizeChange,
                       'change_planet_count'  => $existingGalaxy->planet_count - $existingPlanetCount
                   ];
               }
           }

           try {
              $this->updateGalaxyRanks($tick, $gh);
           }
           catch (Exception $e) {
              Log::error($e->getMessage());
           }

           try {
              $this->updateGalaxyGrowth();
           }
           catch (Exception $e) {
              Log::error($e->getMessage());
           }

           $ids = GalaxyHistory::where('tick', $tick)->get()->pluck('galaxy_id');

           Galaxy::whereNotIn('id', $ids)->delete();

           Tick::where('tick', $tick)->update([
              'galaxy_time' => number_format(microtime(true) - $this->galStart, 2)
           ]);
       }
    }

    private function updateGalaxyGrowth()
    {
        $galaxies = Galaxy::all();

        foreach($galaxies as $galaxy) {
            $score = $galaxy->getOriginal('score');
            $value = $galaxy->getOriginal('value');
            $size = $galaxy->getOriginal('size');
            $xp = $galaxy->getOriginal('xp');
            $planetCount = $galaxy->getOriginal('planet_count');
            $rank_score = $galaxy->rank_score;
            $rank_value = $galaxy->rank_value;
            $rank_size = $galaxy->rank_size;
            $rank_xp = $galaxy->rank_xp;

            $galaxy->growth_score = $score - $galaxy->day_score;
            $galaxy->growth_value = $value - $galaxy->day_value;
            $galaxy->growth_size = $size - $galaxy->day_size;
            $galaxy->growth_xp = $xp - $galaxy->day_xp;
            $galaxy->growth_planet_count = $planetCount - $galaxy->day_planet_count;
            $galaxy->growth_rank_score =  $galaxy->day_rank_score - $rank_score;
            $galaxy->growth_rank_value =  $galaxy->day_rank_value - $rank_value;
            $galaxy->growth_rank_size =  $galaxy->day_rank_size - $rank_size;
            $galaxy->growth_rank_xp =  $galaxy->day_rank_xp - $rank_xp;

            $scoreMod = (strstr($galaxy->growth_score, "-")) ? "-" : "";
            $valueMod = (strstr($galaxy->growth_value, "-")) ? "-" : "";
            $sizeMod = (strstr($galaxy->growth_size, "-")) ? "-" : "";
            $xpMod = (strstr($galaxy->growth_xp, "-")) ? "-" : "";

            $galaxy->growth_percentage_score = ($galaxy->day_score) ? $scoreMod . abs($galaxy->growth_score) / $galaxy->day_score * 100 : 0;
            $galaxy->growth_percentage_value = ($galaxy->day_value) ? $valueMod . abs($galaxy->growth_value) / $galaxy->day_value * 100 : 0;
            $galaxy->growth_percentage_size = ($galaxy->day_size) ? $sizeMod . abs($galaxy->growth_size) / $galaxy->day_size * 100 : 0;
            $galaxy->growth_percentage_xp = ($galaxy->day_xp) ? $xpMod . abs($galaxy->growth_xp) / $galaxy->day_xp * 100 : 0;

            $galaxy->save();
        }
    }

    private function updateGalaxyRanks($tick, $gh)
    {
        $valGals = Galaxy::orderBy('value', 'DESC')->get()->keyBy('id');
        $scoreGals = Galaxy::orderBy('score', 'DESC')->get()->keyBy('id');
        $sizeGals = Galaxy::orderBy('size', 'DESC')->get()->keyBy('id');
        $xpGals = Galaxy::orderBy('xp', 'DESC')->get()->keyBy('id');
        $gals = Galaxy::all()->keyBy('id');

        $valRank = 1;

        foreach($valGals as $id => $galaxy) {
            $gals[$id]->rank_value = $valRank;
            if($this->hour == 0) $gals[$id]->day_rank_value = $valRank;
            $valRank++;
        }

        $scoreRank = 1;

        foreach($scoreGals as $id => $galaxy) {
            $gals[$id]->rank_score = $scoreRank;
            if($this->hour == 0) $gals[$id]->day_rank_score = $scoreRank;
            $scoreRank++;
        }

        $sizeRank = 1;

        foreach($sizeGals as $id => $galaxy) {
            $gals[$id]->rank_size = $sizeRank;
            if($this->hour == 0) $gals[$id]->day_rank_size = $sizeRank;
            $sizeRank++;
        }

        $xpRank = 1;

        foreach($xpGals as $id => $galaxy) {
            $gals[$id]->rank_xp = $xpRank;
            if($this->hour == 0) $gals[$id]->day_rank_xp = $xpRank;
            $xpRank++;
        }

        foreach($gals as $id => $gal) {
            $gal->save();
            if(isset($gh[$id])) {
                $gh[$id]['rank_value'] = $gal->rank_value;
                $gh[$id]['rank_score'] = $gal->rank_score;
                $gh[$id]['rank_xp'] = $gal->rank_xp;
                $gh[$id]['rank_size'] = $gal->rank_size;
            }
        }
        GalaxyHistory::insert($gh);
    }

    private function tickAlliances()
    {
        $this->allyStart = microtime(true);

        try {
            $alliances = file_get_contents(Config::get('dumps.alliances'));
        } catch(Exception $e) {
            return;
        }

       $dump = explode("\n", $alliances);

       $tick = str_replace('Tick: ', '', $dump[3]);

       if(!AllianceHistory::where('tick', $tick)->first()) {

          $this->tick = $tick;
          Tick::create([
              'tick' => $tick,
              'time' => Carbon::now()->minute(0)->second(0),
              'length' => 0
          ]);
           $alliances = array_slice($dump, 8);

           $currentAlliances = Alliance::all()->keyBy('name');

           foreach($alliances as $alliance) {

               $alliance = explode("\t", $alliance);

               if(count($alliance) > 2) {

                   $existingAlliance = isset($currentAlliances[utf8_encode(trim($alliance[1], '"'))]) ? $currentAlliances[utf8_encode(trim($alliance[1], '"'))] : null;

                   if(!$existingAlliance) {
                      $existingAlliance = Alliance::create([
                          'name' => utf8_encode(trim($alliance[1], '"'))
                      ]);
                   }

                   $valueChange = 0;
                   $scoreChange = 0;
                   $sizeChange = 0;
                   $memberChange = 0;
                   $xpChange = 0;

                   $rank = $alliance[0];
                   $name = utf8_encode(trim($alliance[1], '"'));
                   $size = $alliance[2];
                   $members = $alliance[3];
                   $score = $alliance[4];
                   $totalScore = $alliance[6];
                   $points = $alliance[5];
                   $value = $alliance[7];
                   $xp = ($totalScore-$value)/60;

                   if(!empty($existingAlliance)) {
                      $valueChange = $value - $existingAlliance->getOriginal('value');
                      $scoreChange = $score - $existingAlliance->getOriginal('score');
                      $sizeChange = $size - $existingAlliance->getOriginal('size');
                      $xpChange = $xp - $existingAlliance->getOriginal('xp');
                      $memberChange = $members - $existingAlliance->members;
                   }

                   $avgValue = $value / $members;
                   $avgScore = $totalScore / $members;
                   $avgSize = $size / $members;

                   $existingAlliance->value = $value;
                   $existingAlliance->score = $score;
                   $existingAlliance->size = $size;
                   $existingAlliance->avg_value = $avgValue;
                   $existingAlliance->avg_score = $avgScore;
                   $existingAlliance->avg_size = $avgSize;
                   $existingAlliance->members = $members;
                   $existingAlliance->total_score = $totalScore;
                   $existingAlliance->xp = $xp;
                   if($this->hour == 0) {
                      $existingAlliance->day_value = $value;
                      $existingAlliance->day_score = $score;
                      $existingAlliance->day_size = $size;
                      $existingAlliance->day_xp = $xp;
                      $existingAlliance->day_avg_value = $avgValue;
                      $existingAlliance->day_avg_score = $avgScore;
                      $existingAlliance->day_avg_size = $avgSize;
                      $existingAlliance->day_members = $members;
                   }
                   $existingAlliance->save();

                   $ah[$existingAlliance->id] = [
                       'rank'           => $rank,
                       'name'           => $name,
                       'size'           => $size,
                       'members'        => $members,
                       'counted_score'  => $score,
                       'points'         => $points,
                       'total_score'    => $totalScore,
                       'total_value'    => $value,
                       'tick'           => $tick,
                       'alliance_id'    => $existingAlliance->id,
                       'avg_size'       => $size / $members,
                       'avg_value'      => $value / $members,
                       'avg_score'      => $score / $members,
                       'change_value'   => $valueChange,
                       'change_score'   => $scoreChange,
                       'change_size'    => $sizeChange,
                       'change_members' => $memberChange,
                       'change_xp'      => $xpChange,
                       'xp'             => $existingAlliance->xp
                   ];
               }
           }

           try {
              $this->updateAllianceRanks($tick, $ah);
           }
           catch (Exception $e) {
              Log::error($e->getMessage());
           }

           try {
              $this->updateAllianceGrowth();
           }
           catch (Exception $e) {
              Log::error($e->getMessage());
           }

           $ids = AllianceHistory::where('tick', $tick)->get()->pluck('alliance_id');

           Alliance::whereNotIn('id', $ids)->delete();

           Tick::where('tick', $tick)->update([
              'alliance_time' => number_format(microtime(true) - $this->allyStart, 2)
           ]);
       }
    }

    private function updateAllianceGrowth()
    {
        $alliances = Alliance::all();

        foreach($alliances as $alliance) {
            $value = $alliance->getOriginal('value');
            $score = $alliance->getOriginal('score');
            $size = $alliance->getOriginal('size');
            $avgValue = $alliance->getOriginal('avg_value');
            $avgScore = $alliance->getOriginal('avg_score');
            $avgSize = $alliance->getOriginal('avg_size');
            $rank_score = $alliance->rank_score;
            $rank_value = $alliance->rank_value;
            $rank_size = $alliance->rank_size;
            $rank_avg_score = $alliance->rank_avg_score;
            $rank_avg_value = $alliance->rank_avg_value;
            $rank_avg_size = $alliance->rank_avg_size;
            $members = $alliance->day_members;

            $alliance->growth_score = $score - $alliance->day_score;
            $alliance->growth_value = $value - $alliance->day_value;
            $alliance->growth_size = $size - $alliance->day_size;
            $alliance->growth_avg_score = $avgScore - $alliance->day_avg_score;
            $alliance->growth_avg_value = $avgValue - $alliance->day_avg_value;
            $alliance->growth_avg_size = $avgSize - $alliance->day_avg_size;
            $alliance->growth_rank_score =  $alliance->day_rank_score - $rank_score;
            $alliance->growth_rank_value =  $alliance->day_rank_value - $rank_value;
            $alliance->growth_rank_size =  $alliance->day_rank_size - $rank_size;
            $alliance->growth_rank_avg_score =  $alliance->day_rank_avg_score - $rank_avg_score;
            $alliance->growth_rank_avg_value =  $alliance->day_rank_avg_value - $rank_avg_value;
            $alliance->growth_rank_avg_size =  $alliance->day_rank_avg_size - $rank_avg_size;
            $alliance->growth_members = $alliance->members - $members;

            $scoreMod = (strstr($alliance->growth_score, "-")) ? "-" : "";
            $valueMod = (strstr($alliance->growth_value, "-")) ? "-" : "";
            $sizeMod = (strstr($alliance->growth_size, "-")) ? "-" : "";
            $avgscoreMod = (strstr($alliance->growth_avg_score, "-")) ? "-" : "";
            $avgvalueMod = (strstr($alliance->growth_avg_value, "-")) ? "-" : "";
            $avgsizeMod = (strstr($alliance->growth_avg_size, "-")) ? "-" : "";

            $alliance->growth_percentage_score = ($alliance->day_score) ? $scoreMod . number_format(abs($alliance->growth_score) / $alliance->day_score * 100, 1) : 0;
            $alliance->growth_percentage_value = ($alliance->day_value) ? $valueMod . number_format(abs($alliance->growth_value) / $alliance->day_value * 100, 1) : 0;
            $alliance->growth_percentage_size = ($alliance->day_size) ? $sizeMod . number_format(abs($alliance->growth_size) / $alliance->day_size * 100, 1) : 0;
            $alliance->growth_percentage_avg_score = ($alliance->day_avg_score) ? $avgscoreMod . number_format(abs($alliance->growth_avg_score) / $alliance->day_avg_score * 100, 1) : 0;
            $alliance->growth_percentage_avg_value = ($alliance->day_avg_value) ? $avgvalueMod . number_format(abs($alliance->growth_avg_value) / $alliance->day_avg_value * 100, 1) : 0;
            $alliance->growth_percentage_avg_size = ($alliance->day_avg_size) ? $avgsizeMod . number_format(abs($alliance->growth_avg_size) / $alliance->day_avg_size * 100, 1) : 0;

            $alliance->save();
        }
    }

    private function updateAllianceRanks($tick, $ah)
    {
        $sizeAlly = Alliance::orderBy('size', 'DESC')->get()->keyBy('id');
        $valueAlly = Alliance::orderBy('value', 'DESC')->get()->keyBy('id');
        $scoreAlly = Alliance::orderBy('score', 'DESC')->get()->keyBy('id');
        $avgsizeAlly = Alliance::orderBy('avg_size', 'DESC')->get()->keyBy('id');
        $avgvalueAlly = Alliance::orderBy('avg_value', 'DESC')->get()->keyBy('id');
        $avgscoreAlly = Alliance::orderBy('avg_score', 'DESC')->get()->keyBy('id');
        $alliances = Alliance::all()->keyBy('id');

        $rank = 1;

        foreach($sizeAlly as $id => $alliance) {
            $alliances[$id]['rank_size'] = $rank;
            if($this->hour == 0) $alliances[$id]['day_rank_size'] = $rank;
            $rank++;
        }

        $rank = 1;

        foreach($valueAlly as $id => $alliance) {
            $alliances[$id]->rank_value = $rank;
            if($this->hour == 0) $alliances[$id]['day_rank_value'] = $rank;
            $rank++;
        }

        $rank = 1;

        foreach($scoreAlly as $id => $alliance) {
            $alliances[$id]->rank_score = $rank;
            if($this->hour == 0) $alliances[$id]['day_rank_score'] = $rank;
            $rank++;
        }

        $rank = 1;

        foreach($avgsizeAlly as $id => $alliance) {
            $alliances[$id]->rank_avg_size = $rank;
            if($this->hour == 0) $alliances[$id]['day_rank_avg_size'] = $rank;
            $rank++;
        }

        $rank = 1;

        foreach($avgvalueAlly as $id => $alliance) {
            $alliances[$id]->rank_avg_value = $rank;
            if($this->hour == 0) $alliances[$id]['day_rank_avg_value'] = $rank;
            $rank++;
        }

        $rank = 1;

        foreach($avgscoreAlly as $id => $alliance) {
            $alliances[$id]->rank_avg_score = $rank;
            if($this->hour == 0) $alliances[$id]['day_rank_avg_score'] = $rank;
            $rank++;
        }

        foreach($alliances as $id => $alliance) {
            $alliance->save();
            if(isset($ah[$id])) {
                $ah[$id]['rank_value'] = $alliance->rank_value;
                $ah[$id]['rank_score'] = $alliance->rank_score;
                $ah[$id]['rank_size'] = $alliance->rank_size;
                $ah[$id]['rank_avg_value'] = $alliance->rank_avg_value;
                $ah[$id]['rank_avg_score'] = $alliance->rank_avg_score;
                $ah[$id]['rank_avg_size'] = $alliance->rank_avg_size;
            }
        }

        AllianceHistory::insert($ah);
    }
 }
