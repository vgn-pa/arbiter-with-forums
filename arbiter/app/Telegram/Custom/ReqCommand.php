<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Eff;
use App;
use App\Tick;
use App\User;
use App\Planet;
use App\Services\CreateScanRequest;

class ReqCommand extends BaseCommand
{
    protected $command = "!req";

    public function execute()
    {
        $eff = App::make(Eff::class);

        $string = explode(" ", $this->text);

        if(!isset($string[0]) || !isset($string[1])) return "usage: !eff <coords> <types eg. pda>";

        if(!$this->isWebUser()) return "User can not be authenticated with webby.";

        $user = User::where('tg_username', $this->userId)->first();

        preg_match("/^(\d+)[.: ](\d+)[.: ](\d+)$/", $string[0], $planet);

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

            if(!$planet) {
                return sprintf("No planet found at %d:%d:%d", $x, $y, $z);
            }
        }

        $tick = Tick::orderBy('tick', 'DESC')->first();

        $request = App::make(CreateScanRequest::class);

        return $request->setX($x)->setY($y)->setZ($z)
            ->setScanType(str_split($string[1]))
            ->setTick($tick->tick)
            ->setUserId($user->id)
            ->execute();
    }
}