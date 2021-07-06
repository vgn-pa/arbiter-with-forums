<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Alliance;
use App\Planet;
use App\IntelChange;
use App\Tick;
use DB;

class IntelChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pa:checkIntel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A command to check and lock valid intel';

    private $tick = 0;

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
        $tick = Tick::orderBy('tick', 'DESC')->first();
        $this->tick = $tick ? $tick->tick : 0;

        $this->checkIntelComplete();
    }

    private function checkIntelComplete()
    {
        $alliances = Alliance::with('planets')->get();

        foreach($alliances as $alliance) {
            // We have all members added to intel but are they accurate
            if(count($alliance->planets) == $alliance->members) {
                $this->checkAllianceLock($alliance);
            } else {
                $this->notLocked($alliance);
            }
        }
    }

    private function notLocked($alliance)
    {
        $totals = $this->calcTotals($alliance);

        $alliance->size_diff = $alliance->size - $totals['size'];
        $alliance->xp_diff = $alliance->xp - $totals['xp'];
        $alliance->is_locked = 0;
        $alliance->save();

        $intelPlanets = count($alliance->planets);
        $actualPlanets = $alliance->members;

        $changes = false;

        // See if we can match existing planets if only 1 planet missing/extra
        if(($intelPlanets - $actualPlanets) == 1 || ($intelPlanets - $actualPlanets) == -1) {
            // If we're missing 1
            if($intelPlanets < $actualPlanets) {
                if(Planet::where(['size' => abs($alliance->size_diff), 'xp' => abs($alliance->xp_diff)])->count() == 1 && $new = Planet::where(['size' => abs($alliance->size_diff), 'xp' => abs($alliance->xp_diff)])->first()) {
                    $currentAlly = $new->alliance_id;
                    $new->alliance_id = $alliance->id;
                    if($new->alliance_id != $currentAlly) {

                        $new->save();
                        // Save intel change to log
                        IntelChange::create([
                            'planet_id' => $new->id,
                            'alliance_from_id' => $currentAlly,
                            'alliance_to_id' => $new->alliance_id,
                            'tick' => $this->tick
                        ]);
                        $changes = true;
                    }
                }
            }
            // if we have 1 extra
            if($intelPlanets > $actualPlanets) {

                if(Planet::where(['size' => abs($alliance->size_diff), 'xp' => abs($alliance->xp_diff)])->count() == 1 && $new = Planet::where(['size' => abs($alliance->size_diff), 'xp' => abs($alliance->xp_diff)])->first()) {
                    $currentAlly = $new->alliance_id;
                    $new->alliance_id = null;
                    if($new->alliance_id != $currentAlly) {
                        $new->save();
                        // Save intel change to log
                        IntelChange::create([
                            'planet_id' => $new->id,
                            'alliance_from_id' => $currentAlly,
                            'alliance_to_id' => $new->alliance_id,
                            'tick' => $this->tick
                        ]);
                        $changes = true;
                    }
                }
            }
        }
        if($changes) $this->checkIntelComplete();
    }

    /**
    * Checks if the members in intel add up to the alliance total score value and size
    */
    private function checkAllianceLock($alliance)
    {
        $changes = false;

        $totals = $this->calcTotals($alliance);

        if($alliance->size == $totals['size'] && $alliance->xp == $totals['xp']) {
            $alliance->size_diff = 0;
            $alliance->xp_diff = 0;
            $alliance->is_locked = 1;
            $alliance->save();
        } else {
            $alliance->size_diff = $alliance->size - $totals['size'];
            $alliance->xp_diff = $alliance->xp - $totals['xp'];
            $alliance->is_locked = 0;
            $alliance->save();
            // Right numbers in intel but size/xp doesnt match
            if(count($alliance->planets) == $alliance->members) {
                foreach($alliance->planets as $planet) {
                    $size_diff = $alliance->size_diff + $planet->size;
                    $xp_diff = $alliance->xp_diff + $planet->xp;
                    if($new = Planet::where(['size' => $size_diff, 'xp' => $xp_diff])->first()) {
                        $currentAlly = $new->alliance_id;
                        $new->alliance_id = $alliance->id;
                        // Save intel change to log
                        if($new->alliance_id != $currentAlly) {
                            // Change new planet
                            $new->save();
                            IntelChange::create([
                                'planet_id' => $new->id,
                                'alliance_from_id' => $currentAlly,
                                'alliance_to_id' => $new->alliance_id,
                                'tick' => $this->tick
                            ]);
                            // Change old planet
                            $planet->alliance_id = null;
                            $planet->save();
                            IntelChange::create([
                                'planet_id' => $planet->id,
                                'alliance_from_id' => $alliance->id,
                                'alliance_to_id' => null,
                                'tick' => $this->tick
                            ]);
                            $changes = true;
                        }
                    }
                }
                if($changes) $this->checkIntelComplete();
            }
        }
    }

    private function calcTotals($alliance)
    {
      $xp = 0;
      $size = 0;

      foreach($alliance->planets as $planet) {
          $size = $size + $planet->size;
          $xp = $xp + $planet->xp;
      }

      return [
        'xp' => $xp,
        'size' => $size
      ];
    }
}
