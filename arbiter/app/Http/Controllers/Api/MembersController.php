<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Carbon\Carbon;
use App\Planet;
use App\PlanetHistory;
use App\User;
use Twilio;
use App\Role;
use DB;

class MembersController extends ApiController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $enabled = User::with('role', 'planet', 'planet.latestA', 'planet.latestA.au')->orderBy('name', 'ASC')->where(['is_enabled' => 1])->get();
        $disabled = User::with('role', 'planet')->orderBy('name', 'ASC')->where(['is_enabled' => 0, 'is_new' => 0])->get();
        $new = User::with('role', 'planet')->orderBy('name', 'ASC')->where(['is_enabled' => 0, 'is_new' => 1])->get();
        // $admins = User::with('planet', 'planet.latestA', 'planet.latestA.au')->orderBy('name', 'ASC')->where(['is_enabled' => 1, 'is_new' => 0])->get();
        // $bcs = User::with('planet', 'planet.latestA', 'planet.latestA.au')->orderBy('name', 'ASC')->where(['is_enabled' => 1, 'is_new' => 0])->get();

        foreach($enabled as $user) {
            $user->timezone = ($user->timezone) ? Carbon::parse(Carbon::now($user->timezone))->format('H:i:s') : null;
            $user->ships = $this->getShips($user);
            if(env('PHP_TELEGRAM_BOT_API_KEY')) {
                $user->tg_user = "";
                if($user->tg_username) {
                    $user->tg_user = DB::table('user')->where('id', $user->tg_username)->first();
                }
            }
        }

        // foreach($admins as $user) {
        //     $user->timezone = ($user->timezone) ? Carbon::parse(Carbon::now($user->timezone))->format('H:i:s') : null;
        //     $user->ships = $this->getShips($user);
        // }

        $users['enabled'] = $enabled;
        $users['new'] = $new;
        $users['disabled'] = $disabled;
        // $users['admins'] = $admins;
        // $users['bcs'] = $bcs;

        return $users;
    }

    public function show($id)
    {
        if(!$this->hasRole('Admin')) return;

        $user = User::with(['planet' => function($q) {
            $q->select(['id', 'x', 'y', 'z']);
        }])->find($id)->toArray();

        return collect($user);
    }

    public function update($id, Request $request)
    {
        $user = User::find($id);
        $user->fill($request->all());

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

        $user->planet_id = (isset($planet)) ? $planet->id : 0;
        $user->save();

        return $user;
    }

    public function call($id)
    {
        $user = User::find($id);

        //dd(url('/'));
        //$voice = 'http://demo.twilio.com/docs/voice.xml';
        $voice = url('/outgoing');

        if($user->phone) {
            Twilio::call($user->phone, $voice, ['Timeout' => 10]);
            sleep(15);
        }

        return;
    }

    public function enable($id)
    {
        $memberRole = Role::where('name', 'Member')->first();
	$data = $this->show($id);
        User::where('id', $id)->update([
            'is_enabled' => true,
            'is_new' => false,
            'role_id' => $memberRole->id
        ]);
	file_get_contents('https://webby.yourdomain.tld/forums/user-enable.php?username=' . $data['name'] . '&email=' . $data['email']);
        return $this->index();
    }

    public function disable($id)
    {
        User::where('id', $id)->update([
            'is_enabled' => false
        ]);
	$data = $this->show($id);
	file_get_contents('https://webby.yourdomain.tld/forums/user-disable.php?username=' . $data['name'] . '&email=' . $data['email']);
        return $this->index();
    }

    public function admin($id)
    {
        $user = User::find($id);

        $user->is_admin = !$user->is_admin;
        $user->save();

        return $this->index();
    }

    public function role($id, $roleId)
    {
        $user = User::find($id);

        $user->role_id = $roleId;
        $user->save();

        return $this->index();
    }

    public function delete($id)
    {
	$data = $this->show($id);
        if($id != 1) User::where('id', $id)->delete();
	file_get_contents('https://webby.yourdomain.tld/forums/user-disable.php?username=' . $data['name'] . '&email=' . $data['email']);
        return $this->index();
    }

    private function getShips($user)
    {
        $ships = [];

        $totalValue = 0;

        if($user->planet && $user->planet->latestA) {
            foreach($user->planet->latestA->au as $ship) {
                $value = (($ship->ship->metal + $ship->ship->crystal + $ship->ship->eonium) * $ship->amount) / 100;
                $ships[] = [
                    'name' => $ship->ship->name,
                    'amount' => number_format($ship->amount, 0),
                    'value' => $value
                ];
                $totalValue = $totalValue + $value;
            }
        }

        foreach($ships as $key => $ship) {
            $ships[$key]['percentage'] = number_format(($ship['value'] / $totalValue) * 100, 2);
        }

        return $ships;
    }
}
