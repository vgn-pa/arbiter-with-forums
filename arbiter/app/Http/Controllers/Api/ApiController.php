<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Setting;
use App\Tick;
use Auth;
use App\User;
use App\ScanRequest;
use Carbon\Carbon;
use Config;

class ApiController extends Controller
{
    public $settings;
    public $user;

    public function __construct()
    {
        $tick = Tick::orderBy('tick', 'DESC')->first();
        $this->tick = $tick ? $tick->tick : 0;
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            $this->loadSettings();
    
            return $next($request);
        });
        
    }

    protected function loadSettings()
    {
        $data = [];

        $settings = Setting::select('value', 'name')->get()->keyBy('name');

        foreach($settings as $setting) {
            $data[$setting->name] = $setting->value;
        }

        if(!$this->hasRole('Admin') && !$this->hasRole('BC')) {
            unset($data['attack']);
        }

        $data['tick'] = $this->tick;
        $data['role'] = $this->user->role->name;
        $data['user'] = User::with('planet', 'planet.alliance')->where('id', Auth::user()->id)->first();
        $data['appName'] = env('APP_NAME');
        $data['scans'] = ScanRequest::with(['planet', 'user', 'scan'])->whereNull('scan_id')->count();
        $data['myScans'] = ScanRequest::with(['planet', 'user', 'scan'])->where('user_id', Auth::user()->id)->whereNotNull('scan_id')->where('created_at', '>', Carbon::now()->addDays(-1))->count();
        $data['telegram'] = (env('PHP_TELEGRAM_BOT_API_KEY', '')) ? true : false;
        $data['twilio'] = (bool) Config::get('twilio.twilio.enabled');

        $this->settings = $data;
    }

    public function hasRole($role)
    {
        if($this->user->role->name == $role) {
            return true;
        }

        return false;
    }
}
