<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Tick;
use Auth;

class TickController extends ApiController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    public function current()
    {
        return Tick::orderBy('tick', 'DESC')->first();
    }
}
