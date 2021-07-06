<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Eff;
use App;

class WhorulesCommand extends BaseCommand
{
    protected $command = "!whorules";

    public function execute()
    {
        return "Rob, and Rob alone!";
    }
}
