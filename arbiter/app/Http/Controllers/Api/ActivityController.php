<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Activity;
use Carbon\Carbon;

class ActivityController extends ApiController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $from = Carbon::now()->addDays($request->input('days', 0))->startOfDay();
        $to = Carbon::parse($from)->endOfDay();

        $activities = Activity::with(['user' => function($q) {
            $q->select(['id', 'name']);
        }])->whereBetween('created_at', [$from, $to])
            ->orderBy('created_at', 'DESC');

        $userId = $request->input('user_id', null);

        if($userId) {
            $activities->where('user_id', $userId);
        }

        return $activities->paginate(50)->appends(['user_id' => $userId]);
    }
}
