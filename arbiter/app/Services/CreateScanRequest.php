<?php

namespace App\Services;

use App\Planet;
use App\ScanRequest;
use Longman\TelegramBot\Request;
use App\Setting;
use Longman\TelegramBot\Telegram;
use Config;

class CreateScanRequest
{
    private $x;
    private $y;
    private $z;
    private $scanType;
    private $tick;
    private $userId;

    public function setX($x)
    {
        $this->x = $x;
        return $this;
    }

    public function setY($y)
    {
        $this->y = $y;
        return $this;
    }

    public function setZ($z)
    {
        $this->z = $z;
        return $this;
    }

    public function setScanType($scanType)
    {
        $this->scanType = $scanType;
        return $this;
    }

    public function setTick($tick)
    {
        $this->tick = $tick;
        return $this;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function execute()
    {
        $planet = Planet::where([
            'x' => $this->x,
            'y' => $this->y,
            'z' => $this->z
          ])->first();

        if(!$planet) return response()->json(['error' => sprintf('No planet with coords %s:%s:%s found.', $this->x, $this->y, $this->z)], 422);

        $scans = Config::get('scans');

        foreach($this->scanType as $type) {

            if(!$scans[$type]) continue;

            $request = new ScanRequest();
            $request->scan_type = $type;
            $request->tick = $this->tick;
            $request->user_id = $this->userId;
            $request->planet_id = $planet->id;
            $request->save();

            $chatId = Setting::where('name', 'tg_scans_channel')->first();


            if($chatId->value) {
                
                $data = [
                    'chat_id' => $chatId->value,
                    'text'    => "New " . strtoupper($request->scan_type) . " request [ID: " . $request->id . "] on " . $this->x . ":" . $this->y . ":" . $this->z . ": " . $request,
                ];

                $telegram = new Telegram(Config::get('phptelegrambot.bot.api_key'), Config::get('phptelegrambot.bot.name'));
    
                Request::sendMessage($data);
            }
            
        }

        return "Scans requested";
    }
}
