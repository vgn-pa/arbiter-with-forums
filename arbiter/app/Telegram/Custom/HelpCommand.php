<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Eff;
use App;

class HelpCommand extends BaseCommand
{
    protected $command = "!help";

    public function execute()
    {
        return "The following commands are available (so far): !attacks !cost !eff !intel !lookup !req !roidcost !setnick !setplanet !ship !spam !stop !tick !tools";
    }
}