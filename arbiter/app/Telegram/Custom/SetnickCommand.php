<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\User;

class SetnickCommand extends BaseCommand
{
    protected $command = "!setnick";

    public function execute()
    {
        if(!$this->isChannelAllowed()) return "You can only use this command in the channel linked to webby.";

        if(!$this->text) return "usage: !setnick <nick>";
        if(!$user = User::where('name', $this->text)->first()) return "Aw for fucks sake! There's no user on the webby with that name. Check your spelling or something";
        if($user->tg_username) return "That web user has already been linked to a TG user.";
        
        $user->tg_username = $this->message->from['id'];
        $user->save();

        return "Successfully linked TG user to webby user! Welcome and may Rob's Blessing be upon you!";
    }
}