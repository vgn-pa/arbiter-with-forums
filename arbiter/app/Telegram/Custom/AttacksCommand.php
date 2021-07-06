<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App;

class AttacksCommand extends BaseCommand
{
    protected $command = "!attacks";

    public function execute()
    {
        if(!$this->isChannelAllowed()) return "You can not use that command in this channel";

        $attacks = Attack::where([
            'is_closed' => 0,
            'is_opened' => 1
        ])->get();

        if(count($attacks)){
            $urls = [];

            foreach($attacks as $attack) {
                $urls[] = App::make('url')->to('/') . '/#/attacks/' . $attack->attack_id;
            }

            return sprintf("Open attacks: %s", implode(", ", $urls));
        }

        return "There are no open attacks";
    }
}