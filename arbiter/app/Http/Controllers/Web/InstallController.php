<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class InstallController extends Controller
{
    public function index()
    {
        if(User::all()->count()) {
            return redirect('/login');
        }

        return view('install');
    }

    public function store(Request $request)
    {

    }
}
