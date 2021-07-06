<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use DB;

class ResetPa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pa:reset';

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
        DB::table('advanced_unit_scans')->truncate();
        DB::table('alliance_history')->truncate();
        DB::table('alliances')->truncate();
        DB::table('attack_bookings')->truncate();
        DB::table('attack_booking_users')->truncate();
        DB::table('attacks')->truncate();
        DB::table('attack_targets')->truncate();
        DB::table('development_scans')->truncate();
        DB::table('fleet_movements')->truncate();
        DB::table('galaxies')->truncate();
        DB::table('galaxy_history')->truncate();
        DB::table('intel_change')->truncate();
        DB::table('jgp_scans')->truncate();
        DB::table('news_scans')->truncate();
        DB::table('planet_history')->truncate();
        DB::table('planets')->truncate();
        DB::table('planet_scans')->truncate();
        DB::table('planet_movements')->truncate();
        DB::table('scans')->truncate();
        DB::table('ships')->truncate();
        DB::table('ticks')->truncate();
        DB::table('unit_scans')->truncate();
        DB::table('battlegroups')->truncate();
        DB::table('battlegroup_users')->truncate();
        User::whereNotNull('id')->update(['planet_id' => null, 'distorters' => 0, 'military_centres' => 0]);
    }
}
