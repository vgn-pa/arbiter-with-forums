<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\Planet;
use App\User;

class LookupCommand extends BaseCommand
{
    protected $command = "!lookup";

    public function execute()
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";

        // No text, try to find the users planet
        if(!$this->text) {
            $user = User::with('planet')->where('tg_username', $this->message->from['id'])->first();
            if(!$user) {
                return "You must link your TG account to your web user using !setnick <web username>.";
            }
            if(!$user->planet) {
                return "You haven't set your coords on webby, use !setplanet <x:y:z>.";
            }
            return $user->planet;
        }

        preg_match("/^(\d+)[.: ](\d+)[.: ](\d+)$/", $this->text, $planet);

        $psearch = ($planet) ? $planet : false;

        if($psearch) {
            $x = $psearch[1];
            $y = $psearch[2];
            $z = $psearch[3];

            $planet = Planet::with('alliance')->where([
              'x' => $x,
              'y' => $y,
              'z' => $z
            ])->first();

            if($planet) {
                return $planet;
            } else {
                return sprintf("No planet found at %d:%d:%d", $x, $y, $z);
            }
        }

        return "usage: !lookup <x:y:z>";
    }
}