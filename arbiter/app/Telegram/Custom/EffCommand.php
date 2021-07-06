<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Eff;
use App;

class EffCommand extends BaseCommand
{
    protected $command = "!eff";

    public function execute()
    {
        $eff = App::make(Eff::class);

        $string = explode(" ", $this->text);

        if(!isset($string[0]) || !isset($string[1])) return "usage: !eff <amount> <ship>";

        return $eff->setName($string[1])
                ->setAmount($string[0])
                ->execute();
    }
}