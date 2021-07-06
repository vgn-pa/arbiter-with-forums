<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Cost;
use App;

class CostCommand extends BaseCommand
{
    protected $command = "!cost";

    public function execute()
    {
        $cost = App::make(Cost::class);

        $string = explode(" ", $this->text);

        if(!$string[0] || !$string[1]) return "usage: !cost <amount> <ship>";

        return $cost->setShip($string[1])
                ->setAmount($string[0])
                ->execute();
    }
}