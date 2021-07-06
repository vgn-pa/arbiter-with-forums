<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\Ship;

class ShipCommand extends BaseCommand
{
    protected $command = "!ship";

    public function execute()
    {
        if(!$ship = Ship::where('name', $this->text)->first()) return "There's no ship with that name.";
        
        $reply = sprintf("%s (%s) is class %s | Target 1: %s |", $ship->name, $ship->race, $ship->class, $ship->t1);
        if($ship->t2) $reply .= sprintf(" Target 2: %s |", $ship->t2);
        if($ship->t3) $reply .= sprintf(" Target 3: %s |", $ship->t3);
        $reply .= sprintf(" Type %s | Init: %s |", $ship->type, $ship->init);
        $reply .= sprintf(" EMPres: %s |", $ship->empres);
        if($ship->type == 'EMP') {
            $reply .= sprintf(" Guns: %s |", $ship->guns);
        } else {
            $reply .= sprintf(" D/C: %s |", (floor($ship->damage*10000/$ship->total_cost)));
            $reply .= sprintf(" A/C: %s", (floor($ship->armor*10000/$ship->total_cost)));
        }

        return $reply;
    }
}