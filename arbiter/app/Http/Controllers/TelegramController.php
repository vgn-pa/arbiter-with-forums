<?php
namespace App\Http\Controllers;

use Log;

use PhpTelegramBot\Laravel\PhpTelegramBotContract;

class TelegramController extends Controller {

    public function set(PhpTelegramBotContract $telegram_bot) 
    {
        return $telegram_bot->setWebhook(url(config('phptelegrambot.bot.api_key') . '/hook'));
    }

    public function delete(PhpTelegramBotContract $telegram_bot)
    {
        return $telegram_bot->deleteWebHook();
    }

    public function hook(PhpTelegramBotContract $telegram_bot) 
    {
        $telegram_bot->handle();
    }

    public function updates(PhpTelegramBotContract $telegram_bot) 
    {
        $telegram_bot->useGetUpdatesWithoutDatabase();

        return $telegram_bot->handleGetUpdates();
    }

}