<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\BattleGroup;
use Carbon\Carbon;

class CalculateBattlegroups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pa:calculateBattlegroups';

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
        $hour = Carbon::now()->hour;

        $bgs = BattleGroup::with(['members', 'members.planet', 'members.planet.lastTick'])->get();

        foreach($bgs as $bg) {

            if($hour == 0) {
                $bg->growth_size = 0;
                $bg->growth_value = 0;
                $bg->growth_score = 0;
                $bg->growth_xp = 0;
                $bg->save();
            }

            foreach($bg->members as $member) {

                if(!$member->planet || !$member->planet->lastTick) continue;

                $bg->size = $bg->size + $member->planet->lastTick->change_size;
                $bg->value = $bg->value + $member->planet->lastTick->change_value;
                $bg->score = $bg->score + $member->planet->lastTick->change_score;
                $bg->xp = $bg->xp + $member->planet->lastTick->change_xp;
                $bg->growth_size = $bg->growth_size + $member->planet->lastTick->change_size;
                $bg->growth_value = $bg->growth_value + $member->planet->lastTick->change_value;
                $bg->growth_score = $bg->growth_score + $member->planet->lastTick->change_score;
                $bg->growth_xp = $bg->growth_xp + $member->planet->lastTick->change_xp;

                $scoreMod = (strstr($bg->growth_score, "-")) ? "-" : "";
                $valueMod = (strstr($bg->growth_value, "-")) ? "-" : "";
                $sizeMod = (strstr($bg->growth_size, "-")) ? "-" : "";
                $xpMod = (strstr($bg->growth_xp, "-")) ? "-" : "";

                $bg->growth_percentage_score = ($bg->growth_score) ? $scoreMod . abs($bg->growth_score) / $bg->score * 100 : 0;
                $bg->growth_percentage_value = ($bg->growth_value) ? $valueMod . abs($bg->growth_value) / $bg->value * 100 : 0;
                $bg->growth_percentage_size = ($bg->growth_size) ? $sizeMod . abs($bg->growth_size) / $bg->size * 100 : 0;
                $bg->growth_percentage_xp = ($bg->growth_xp) ? $xpMod . abs($bg->growth_xp) / $bg->xp * 100 : 0;

                $bg->save();
            }

        }
    }
}
