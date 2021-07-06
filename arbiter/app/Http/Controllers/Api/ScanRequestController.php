<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Auth;
use App\Services\CreateScanRequest;
use App\ScanRequest;
use Carbon\Carbon;
use Config;
use Longman\TelegramBot\Request as TgRequest;
use App\Setting;
use Longman\TelegramBot\Telegram;

class ScanRequestController extends ApiController
{
    public function index()
    {
        return ScanRequest::with(['planet', 'user', 'scan'])
          ->whereNull('scan_id')
          ->orderBy('created_at', 'desc')
          ->get();
    }

    public function store(Request $request, CreateScanRequest $create)
    {
        return $create
          ->setX($request->input('x'))
          ->setY($request->input('y'))
          ->setZ($request->input('z'))
          ->setScanType($request->input('scan_type'))
          ->setUserId(Auth::user()->id)
          ->setTick($this->tick)
          ->execute();
    }

    public function show()
    {

    }

    public function destroy($id, Request $request)
    {
        $request = ScanRequest::find($id);

        if(!$this->hasRole('Admin') && Auth::user()->id != $request->user_id) return;

        $request->delete();

        if($request->scan_id) return;

        $chatId = Setting::where('name', 'tg_scans_channel')->first();

        $scans = Config::get('scans');

        if($chatId->value) {
            
            $data = [
                'chat_id' => $chatId->value,
                'text'    => "Request " . $request->id . " deleted"
            ];

            $telegram = new Telegram(Config::get('phptelegrambot.bot.api_key'), Config::get('phptelegrambot.bot.name'));

            TgRequest::sendMessage($data);
        }
    }

    public function my()
    {
        return ScanRequest::with(['planet', 'user', 'scan'])
          ->where('user_id', Auth::user()->id)
          ->where('created_at', '>', Carbon::now()->addDays(-1))
          ->orderBy('created_at', 'desc')
          ->get();
    }
}
