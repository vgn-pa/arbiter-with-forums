<?php
namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Request;
use App;
use Log;

class WhoamiCommand extends SystemCommand {
	protected $name = 'whoami';
	protected $usage = '/whoami';

	public function execute()
	{
        $message = $this->getMessage();

        $chat_id = $message->getChat()->getId();
        //$text    = App::make('url')->to('/');
        $userId  = $message->from['id'];

        $data = [
            'chat_id' => $chat_id,
            'text'    => $userId,
        ];

        return Request::sendMessage($data);
	}
}