<?php
namespace App\Services\Scans;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use Config;
use App\Scan;

trait ScanTrait {

    protected $scan;
    protected $scanId;
    protected $planetId;
    protected $tick;

    public function setScan($scan)
    {
        $this->scan = $scan;
        return $this;
    }

    public function setScanId($scanId)
    {
        $this->scanId = $scanId;
        return $this;
    }

    public function setTick($tick)
    {
        $this->tick = $tick;
        return $this;
    }

    public function setPlanetId($planetId)
    {
        $this->planetId = $planetId;
        return $this;
    }

    public function setTime($time)
    {
    $this->time = $time;
    return $this;
    }

    public function requestFulfilled($req)
    {
        $chatId = $req->user->tg_username;

        $scan = Scan::find($req->scan_id);

        $scan = "https://game.planetarion.com/showscan.pl?scan_id=" . $scan->pa_id;

        if($chatId) {
            
            $data = [
                'chat_id' => $chatId,
                'text'    => strtoupper($req->scan_type) . " scan on " . $req->planet->x . ":" . $req->planet->y . ":" . $req->planet->z . " complete: " . $scan
            ];

            $telegram = new Telegram(Config::get('phptelegrambot.bot.api_key'), Config::get('phptelegrambot.bot.name'));

            Request::sendMessage($data);
        }
    }
 }
