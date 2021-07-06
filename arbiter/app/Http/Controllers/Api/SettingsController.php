<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Tick;
use App\Setting;
use Auth;
use App\User;
use App\ScanRequest;
use Carbon\Carbon;

class SettingsController extends ApiController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->settings;
    }

    public function current()
    {
        return Tick::orderBy('tick', 'DESC')->first();
    }
}
