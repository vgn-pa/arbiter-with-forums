<?php
namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Request;
use App;
use Log;

class StartCommand extends SystemCommand {
	protected $name = 'start';
	protected $usage = '/start';

	public function execute()
	{
        $message = $this->getMessage();

		$chat_id = $message->getChat()->getId();
		$text    = 'Hi! Welcome to my bot!';

		$data = [
				'chat_id' => $chat_id,
				'text'    => $text,
		];

		return Request::sendMessage($data);
	}
}