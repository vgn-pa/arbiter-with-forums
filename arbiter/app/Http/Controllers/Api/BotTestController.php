<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\User;
use App\Planet;
use App\PlanetHistory;
use Auth;
use Config;
use App\Setting;
use App;

class BotTestController extends ApiController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = $request->input('input');

        $commandText = strtok(substr($input, 1), " ");

        $commands = Config::get('phptelegrambot.commands.map');

        $command = isset($commands[$commandText]) ? App::make($commands[$commandText]) : false;

        $channel = Setting::where('name', 'tg_channels')->first();

        if($command) {
            return $command->setMessage([])
                ->setChatId($channel)
                ->setUserId($command->admin)
                ->setText($input)
                ->execute();
        } else {
            return "That command doesn't exist.";
        }
    }
}
