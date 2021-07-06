<?php

namespace App\Telegram\Custom;

use App\Setting;
use Config;
use App\User;

class BaseCommand
{
    public $chatId;

    public $message;

    public $userId;

    public $admin;

    public function __construct()
    {
        $this->admin = Config::get('phptelegrambot.admins')[0];
    }

    public function setMessage($message)
    {
        $this->message  = $message;
        return $this;
    }

    public function setChatId($chatId)
    {
        $this->chatId = $chatId;
        return $this;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function setText($text)
    {
        $this->text = ltrim(str_replace($this->command, "", $text));
        return $this;
    }
    
    public function isChannelAllowed()
    {
        $channels = Setting::where('name', 'tg_channels')->first();

        if($channels && !empty($channels->value)) { 
            $channels = unserialize($channels->value);
        } else {
            $channels = [];
        }

        if(in_array($this->chatId, $channels)) return true;

        return false;
    }

    public function isWebUser()
    {
        $user = User::where('tg_username', $this->userId)->first();

        if($user) return true;

        return false;
    }

    public function isAdmin()
    {
        if($this->userId == $this->admin) return true;

        return false;
    }
}

