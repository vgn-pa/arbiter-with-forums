<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Planet;
use App\Alliance;
use App\Galaxy;

class HiddenResources extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pa:hiddenResources';

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
        $planets = Planet::with(['latestP'])->get();

        foreach($planets as $planet) {
            if($planet->latestP) {
                $metal = str_replace(',', '', $planet->latestP->res_metal);
                $crystal = str_replace(',', '', $planet->latestP->res_crystal);
                $eonium = str_replace(',', '', $planet->latestP->res_eonium);
                $planet->stock_resources = $metal + $crystal + $eonium;
                $planet->prod_resources = str_replace(',', '', $planet->latestP->prod_res);
                $planet->save();
            }
        }

        $alliances = Alliance::all();

        foreach($alliances as $alliance) {

            $alliance->hidden_resources = Planet::where('alliance_id', $alliance->id)->sum('stock_resources');
            $alliance->prod_resources = Planet::where('alliance_id', $alliance->id)->sum('prod_resources');
            $alliance->save();
        }

        $galaxies = Galaxy::all();

        foreach($galaxies as $galaxy) {

            $galaxy->hidden_resources = Planet::where('galaxy_id', $galaxy->id)->sum('stock_resources');
            $galaxy->prod_resources = Planet::where('galaxy_id', $galaxy->id)->sum('prod_resources');
            $galaxy->save();
        }

    }
}
