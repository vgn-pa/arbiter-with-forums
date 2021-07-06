<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\User;
use App\Planet;

class SetplanetCommand extends BaseCommand
{
    protected $command = "!setplanet";

    public function execute()
    {
        if(!$user = User::where('tg_username', $this->message->from['id'])->first()) return "Fucking hell! You have not linked your web and TG accounts, first use !setnick <web username>.";
        
        preg_match("/^(\d+)[.: ](\d+)[.: ](\d+)$/", $this->text, $planet);

        $psearch = ($planet) ? $planet : false;

        if($psearch) {
            $x = $psearch[1];
            $y = $psearch[2];
            $z = $psearch[3];

            $planet = Planet::where([
              'x' => $x,
              'y' => $y,
              'z' => $z
            ])->first();

            if(!$planet) {
                return sprintf("No fucking planet can be found at %d:%d:%d", $x, $y, $z);
            } else {
                $user->planet_id = $planet->id;
                $user->save();

                return "Planet Set!";
            }
        }

        return "usage: !setplanet <x:y:z>";
    }
}