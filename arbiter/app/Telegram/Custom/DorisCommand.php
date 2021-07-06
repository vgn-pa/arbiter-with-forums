<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Doris;
use App;

class DorisCommand extends BaseCommand
{
    protected $command = "!doris";

    public function execute()
    {
        return "https://vgnpa.uk/images/misc/doris.jpg";
    }
}