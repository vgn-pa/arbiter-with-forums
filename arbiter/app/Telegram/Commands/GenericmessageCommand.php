<?php
namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Request;
use App\Telegram\Custom\IntelCommand;
use App\Telegram\Custom\AttacksCommand;
use App\Telegram\Custom\ShipCommand;
use App\Telegram\Custom\SetnickCommand;
use App\Telegram\Custom\SetplanetCommand;
use App\Telegram\Custom\LookupCommand;
use App\Telegram\Custom\ToolsCommand;
use App\Telegram\Custom\EffCommand;
use App\Telegram\Custom\StopCommand;
use App\Telegram\Custom\HelpCommand;
use App;
use Config;
use Log;

class GenericmessageCommand extends SystemCommand {

	public function execute()
	{
        $message = $this->getMessage();

        $chat_id = $message->getChat()->getId();

        $text = $message->getText();

        $response = "";

        if(substr($text, 0, 1) == "!") {
            $response = $this->parseCommand($message, $text);
        }

        if($response) {
            return Request::sendMessage([
                'chat_id' => $chat_id,
                'text'    => $response,
            ]);
        }
    }
    
    private function parseCommand($message, $text)
    {
        $commandText = strtok(substr($text, 1), " ");

        $commands = Config::get('phptelegrambot.commands.map');

        $command = isset($commands[$commandText]) ? App::make($commands[$commandText]) : false;

        if($command) {
            return $command->setMessage($message)
                ->setChatId($message->getChat()->getId())
                ->setUserId($message->from['id'])
                ->setText($text)
                ->execute();
        } else {
            return "That command doesn't exist.";
        }
    }
}