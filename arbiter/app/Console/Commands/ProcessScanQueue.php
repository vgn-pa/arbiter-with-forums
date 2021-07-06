<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ScanQueue;
use App\Services\ScanParser;
use App;
use Config;

class ProcessScanQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pa:processScans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process the command queue, this will do 2 at a time';

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
        $scanUrl = Config::get('pa.scan_url');

        while(ScanQueue::where('processed', 0)->orderBy('id', 'ASC')->count()) {
            $scans = ScanQueue::where('processed', 0)->orderBy('id', 'ASC')->limit(2)->get();

            $parser = App::make(ScanParser::class);

            foreach($scans as $scan) {
                $parser->parse($scanUrl . $scan->scan_id);
                $scan->processed = 1;
                $scan->save();
            }

            sleep(1);
        }

    }
}
