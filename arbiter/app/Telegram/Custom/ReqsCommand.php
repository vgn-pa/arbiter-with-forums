<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App;
use App\ScanRequest;
use App\Setting;

class ReqsCommand extends BaseCommand
{
    protected $command = "!reqs";

    public function execute()
    {
        $chatId = Setting::where('name', 'tg_scans_channel')->first();

        if($this->chatId != $chatId->value) return "You can only use that command in the designated scan channel.";

        $requests = ScanRequest::with(['planet'])
            ->whereNull('scan_id')
            ->orderBy('created_at', 'desc')
            ->get();

        $reqs = [];

        foreach($requests as $request) {
            $reqs[] = $request;
        }

        return "Open requests: \n" . implode("\n", $reqs);
    }
}