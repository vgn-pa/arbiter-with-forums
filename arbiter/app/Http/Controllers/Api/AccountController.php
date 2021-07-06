<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\User;
use App\Planet;
use App\PlanetHistory;
use Auth;

class AccountController extends ApiController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::with('planet')->find(Auth::user()->id)->toArray();

        if($user['planet']) {
            $user['planet'] = PlanetHistory::where('planet_id', $user['planet']['id'])->orderBy('tick', 'DESC')->first();
        }

        return collect($user);
    }

    public function store(Request $request)
    {
        if($request->input('x') && $request->input('y') && $request->input('z')) {

            $planet = PlanetHistory::where('x', $request->input('x'))
              ->where('y', $request->input('y'))
              ->where('z', $request->input('z'))
              ->orderBy('tick', 'DESC')
              ->first();

            if($planet) {
                $planet = Planet::find($planet->planet_id);
            }
        }

        return User::where('id', Auth::user()->id)->update([
            'name'              => $request->input('name'),
            'planet_id'         => (isset($planet)) ? $planet->id : 0,
            'timezone'          => $request->input('timezone'),
            'phone'             => $request->input('phone'),
            'notes'             => $request->input('notes'),
            'distorters'        => $request->input('distorters'),
            'military_centres'  => $request->input('military_centres'),
            'stealth'           => $request->input('stealth'),
            'allow_calls'       => $request->input('allow_calls')
        ]);
    }
}
