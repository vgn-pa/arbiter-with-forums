<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\Alliance;
use App\Planet;
use App;

class SpamCommand extends BaseCommand
{
    protected $command = "!spam";

    public function execute()
    {
        if(!$this->isChannelAllowed()) return "You can not use that command in this channel";

        if($alliance = Alliance::where('name', 'like', '%' . $this->text . '%')->orWhere('nickname', $this->text)->first()) {
            $coords = [];
            $planets = Planet::where('alliance_id', $alliance->id)->get();
            foreach($planets as $planet) {
                $coords[] = $planet->x . ":" . $planet->y . ":" . $planet->z;
            }
            return sprintf("Spam for alliance " . $alliance->name . ": " . implode(" ", $coords));
        } else {
            return "There is no alliance matching that name or alias.";
        }
    }
}