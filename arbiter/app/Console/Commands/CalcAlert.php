<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Planet;
use App\Tick;

class CalcAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pa:calcAlert';

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
        $tick = Tick::orderBy('tick', 'DESC')->first();

        $planets = Planet::with('latestP', 'latestP.scan', 'latestD', 'latestD.scan')
          ->whereHas('latestP.scan', function($query) use($tick) {
              $query->where('tick', '>', $tick->tick-24);
          })
          ->whereHas('latestD.scan', function($query) use($tick) {
              $query->where('tick', '>', $tick->tick-24);
          })
          ->get();

        foreach($planets as $planet) {
            if($planet->latestP && $planet->latestD) {
                $secCentres = (int) $planet->latestD->security_centre;
                $totalCons = (int) $planet->total_cons;
                $guards = (int) str_replace(',', '', $planet->latestP->guards);
                $roidCount = (int) str_replace(',', '', $planet->latestP->roid_metal) + (int) str_replace(',', '', $planet->latestP->roid_crystal) + (int) str_replace(',', '', $planet->latestP->roid_eonium);

                $first = (50+5*(min(($guards/($roidCount+1)), 15)));
                $min = (1+(($secCentres*2.75) + -10 + 0)/100);
                $max = (1+(($secCentres*2.75) + 25 + 50)/100);

                $minAlert = $first * $min;
                $maxAlert = $first * $max;

                $planet->min_alert = $minAlert;
                $planet->max_alert = $maxAlert;
                $planet->save();
            }
        }
    }
}
