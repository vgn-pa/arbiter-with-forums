<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Config;
use App\Ship;

class ParseShipStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:shipstats';

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
        $url = Config::get('stats.ships');

        $html = file_get_contents($url);

        $ships = simplexml_load_string($html);

        if($ships) {

            Ship::truncate();

            foreach($ships as $ship) {
                Ship::create([
                    'name' => $ship->name,
                    'class' => $ship->class,
                    't1' => $ship->target1,
                    't2' => ($ship->target2 != "-") ? $ship->target2 : null,
                    't3' => ($ship->target3 != "-") ? $ship->target3 : null,
                    'type' => $ship->type,
                    'init' => $ship->initiative,
                    'guns' => $ship->guns,
                    'armor' => $ship->armor,
                    'damage' => ($ship->damage != "-") ? $ship->damage : 0,
                    'empres' => $ship->empres,
                    'metal' => $ship->metal,
                    'crystal' => $ship->crystal,
                    'eonium' => $ship->eonium,
                    'total_cost' => $ship->metal + $ship->crystal + $ship->eonium,
                    'race' => $ship->race,
                    'eta' => $ship->baseeta
                ]);
            }
        }


    }
}
