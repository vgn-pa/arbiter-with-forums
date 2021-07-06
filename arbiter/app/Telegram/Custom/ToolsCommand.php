<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App;

class ToolsCommand extends BaseCommand
{
    protected $command = "!tools";

    public function execute()
    {
        return App::make('url')->to('/');
    }
}