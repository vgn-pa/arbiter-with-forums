<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use View;
use App\Http\Controllers\Controller;
use App\Tick;
use Auth;
use App\User;

class UserscriptController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('userscript');
    }

    public function script()
    {
        $contents = View::make('pascript');
        return response($contents)->header('Content-Type', 'application/javascript');
    }
}
