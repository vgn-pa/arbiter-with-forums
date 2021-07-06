<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Eff;
use App;

class TitpicsCommand extends BaseCommand
{
    protected $command = "!titpics";

    public function execute()
    {
        return "Fuck off to Pornhub you dirty bastard!";
    }
}
