<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Eff;
use App;

class RobCommand extends BaseCommand
{
    protected $command = "!rob";

    public function execute()
    {
        return "Glory be unto the RobGod! the AllFather! the Creator! the only cunt here I actually like!";
    }
}
