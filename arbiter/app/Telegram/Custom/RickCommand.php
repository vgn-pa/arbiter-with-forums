<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Rick;
use App;

class RickCommand extends BaseCommand
{
    protected $command = "!rick";

    public function execute()
    {
        return "https://www.youtube.com/watch?v=dQw4w9WgXcQ";
    }
}