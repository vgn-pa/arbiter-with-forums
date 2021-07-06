<?php
namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Request;
use App\Setting;
use App;
use Config;
use App\Telegram\CommandTrait;

class AddchannelCommand extends SystemCommand {
        protected $name = 'addchannel';
        protected $usage = '/addchannel';

        public function execute()
        {
                $admin = Config::get('phptelegrambot.admins')[0];

                $message = $this->getMessage();
                $chatId = $message->getChat()->getId();

                if(!$this->isAdmin()) return Request::sendMessage(['chat_id' => $chatId, 'text' => "This command can only be used by admins."]);

                if($tgChannels = Setting::where('name', 'tg_channels')->first()->value) {
                        $tgChannels = unserialize($tgChannels);
                } else {
                        $tgChannels = [];
                }

                if(!in_array($chatId, $tgChannels)) {
                        $tgChannels[] = $chatId;
                } else {
                        return Request::sendMessage(['chat_id' => $chatId, 'text' => "This channel is already added."]); 
                }

                Setting::where('name', 'tg_channels')->update(['value' => serialize($tgChannels)]);

                $data = [
                        'chat_id' => $chatId,
                        'text'    => "Channel added, only members of this channel will be able to use certain commands on the bot.",
                ];

                return Request::sendMessage($data);
        }

        private function isAdmin()
        {
                $admin = Config::get('phptelegrambot.admins')[0];
                $message = $this->getMessage();
                $chatId = $message->getChat()->getId();

                $userId  = $message->from['id'];

                if($admin == $userId) return true;

                return false;
        }
}