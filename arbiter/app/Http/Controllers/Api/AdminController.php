<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Alliance;
use Auth;
use App\Setting;
use Artisan;

class AdminController extends ApiController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = Setting::all();

        $all = [];

        foreach($settings as $item) {
            $all[$item->name] = $item->value;
        }

        if(!$this->hasRole('Admin')) {
            unset($all['attack']);
        }

        return collect($all);
    }

    public function store(Request $request)
    {
        $overview = $request->input('overview');
        $alliance = $request->input('alliance');
        $attack = $request->input('attack');

        Setting::where('name', 'attack')->update([
            'value' => $attack
        ]);

        Setting::where('name', 'overview')->update([
            'value' => $overview
        ]);

        Setting::where('name', 'alliance')->update([
            'value' => $alliance
        ]);

        return $this->index();
    }

    public function alliances(Request $request)
    {
        $alliances = $request->get('alliances');

        foreach($alliances as $alliance) {
            Alliance::where('id', $alliance['id'])->update(['nickname' => $alliance['nickname'], 'relation' => $alliance['relation']]);
        }

        return $alliances;
    }

    public function reset()
    {
        if($this->hasRole('Admin')) {
            Artisan::call('pa:reset');
            Artisan::call('parse:shipstats');
        }

        return;
    }
}
