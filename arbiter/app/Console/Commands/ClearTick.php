<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\PlanetHistory;
use App\GalaxyHistory;
use App\AllianceHistory;
use App\Tick;

class ClearTick extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:tick {tick}';

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
        $tick = $this->argument('tick');

        PlanetHistory::where('tick', $tick)->delete();
        GalaxyHistory::where('tick', $tick)->delete();
        AllianceHistory::where('tick', $tick)->delete();
        Tick::where('tick', $tick)->delete();
    }
}
