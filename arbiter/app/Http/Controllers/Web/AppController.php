<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Tick;
use Auth;
use App\User;

class AppController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('layouts.app');
    }
}
