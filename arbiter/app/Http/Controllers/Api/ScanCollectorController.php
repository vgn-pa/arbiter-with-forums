<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\ScanQueue;
use Carbon\Carbon;
use App\Planet;
use App\Scan;

class ScanCollectorController extends ApiController
{
    public function __construct()
    {

    }
    
    public function index(Request $request)
    {
        $type = $request->input('type');
        $id = $request->input('id');

        if(!$type || !$id) return;

        $ids = [];

        if($type == 'alliance') $ids = Planet::where('alliance_id', $id)->pluck('id');
        if($type == 'galaxy') $ids = Planet::where('galaxy_id', $id)->pluck('id');
        if($type == 'planet') $ids = Planet::where('id', $id)->pluck('id');

        $scans = Scan::whereIn('planet_id', $ids)->get();

        $scanTicks = [];

        foreach($scans as $scan) {
            $scanTicks[$scan->tick][] = $scan;
        }

        krsort($scanTicks);

        return $scanTicks;
    }

    public function store(Request $request)
    {
        $ids = $request->input('ids');

        $ids = array_unique($ids);

        $existing = ScanQueue::whereIn('scan_id', $ids)->get()->keyBy('scan_id')->toArray();

        $newScans = [];

        $now = Carbon::now();

        foreach($ids as $id) {
            if(!isset($existing[$id]) && $id) {
                $newScans[] = [
                    'scan_id' => $id,
                    'updated_at' => $now,
                    'created_at' => $now
                ];
            }
        }

        ScanQueue::insert($newScans);

        return response()->json();
    }
}
