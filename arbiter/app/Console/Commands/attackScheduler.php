<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;
use App\Attack;

class attackScheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pa:attackScheduler';

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
        // get current hour
        $hour = Carbon::now()->hour;

        // check if any attacks scheduled to open now
        Attack::where(['is_opened' => 0, 'scheduled_time' => $hour])->update([
            'is_opened' => 1
        ]);
    }
}
