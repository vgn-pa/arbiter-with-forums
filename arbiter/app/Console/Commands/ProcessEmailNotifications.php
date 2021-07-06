<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ScanQueue;
use App\Services\ScanParser;
use App;
use Config;
use App\FleetMovement;
use App\User;
use App\Planet;
use Longman\TelegramBot\Request;
use App\Setting;
use Longman\TelegramBot\Telegram;

class ProcessEmailNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pa:processNotifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process email notifications';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // If notifications aren't enabled, don't do anything
        if(!$enabled = Config::get('notifications.email_notifications.enabled')) return;

        $email = Config::get('notifications.email_notifications.email_address');
        $password = Config::get('notifications.email_notifications.email_password');

        $mailbox = new \PhpImap\Mailbox(
            '{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX', // IMAP server and mailbox folder
            $email, // Username for the before configured mailbox
            $password, // Password for the before configured username
            false
        );

        // Looking for unseen emails from the PA email address
        $searchString = 'UNSEEN FROM "' . Config::get('notifications.email_notifications.pa_email') . '"';

        try {
            // Get all emails (messages)
            // PHP.net imap_search criteria: http://php.net/manual/en/function.imap-search.php
            $mailIds = $mailbox->searchMailbox($searchString);
        } catch(PhpImap\Exceptions\ConnectionException $ex) {
            echo "IMAP connection failed: " . $ex;
            die();
        }
        
        // If $mailsIds is empty, no emails could be found
        if(!$mailIds) {
            return;
        }

        $subjectPattern = "/Planetarion Notifications Mail For Tick (\d+)/";
        $incomingPattern = "/We have detected an open jumpgate from (.*), located at (\d+):(\d+):(\d+). The fleet will approach our system in tick (\d+) and appears to have (\d+) visible ships\./";

        foreach($mailIds as $id) {

            $mail = $mailbox->getMail($id);

            // Check that it's a PA notification
            $subject = $mail->subject;

            preg_match($subjectPattern, $subject, $subjectMatch);

            // If we cant match the subject, skip the email
            if(!$subjectMatch) {
                //$mailbox->markMailAsRead($id);
                print "Can't match subject, skipping\n";
                continue;
            }

            $tick = $subjectMatch[1];

            // Get the user id the notification is for, from the recipient address
            $to = $mail->toString;
            //$to = "ib.pastats+1@gmail.com";

            preg_match('/[+](\d+)[@]/', $to, $userId);

            // This is the user id on webby
            $userId = isset($userId[1]) ? $userId[1] : 0;

            print($userId . "\n");

            // Get the users coords from user account
            if($userId) {
                $user = User::with('planet')->find($userId);
                if(!isset($user->planet_id)) {
                    $mailbox->markMailAsRead($id);
                    //print "Can't get users coords";
                    continue;
                }
            } else {
                // Can't match notification to a user account so can't get coords
                //print "Can't match notification to user account so can't get coords\n";
                $mailbox->markMailAsRead($id);
                continue;
            }

            // Get the notifications from the email body
            $body = $mail->textPlain;
            $notifications = explode("\r\n", $body);

            foreach($notifications as $notification) {

                preg_match($incomingPattern, $notification, $incoming);

                if($incoming) {

                    $fullNotification = $incoming[0];
                    $fleetName = $incoming[1];
                    $x = $incoming[2];
                    $y = $incoming[3];
                    $z = $incoming[4];
                    $landTick = $incoming[5];
                    $visibleShips = $incoming[6];
                    $planetFrom = Planet::where(['x' => $x, 'y' => $y, 'z' => $z])->first();
                    $planetTo = $user->planet;
                    $eta = ($landTick - $tick);

                    // Create the fleet movement
                    if(!$movement = FleetMovement::where([
                        'land_tick'   => $landTick,
                        'planet_from_id' => $planetFrom->id,
                        'planet_to_id' => $planetTo->id
                    ])->first()) {
                        FleetMovement::create([
                            'launch_tick' => $tick,
                            'fleet_name' => $fleetName,
                            'land_tick' => $landTick,
                            'planet_from_id' => $planetFrom->id,
                            'planet_to_id' => $planetTo->id,
                            'mission_type' => 'Attack',
                            'tick' => $tick,
                            'eta' => $eta,
                            'ship_count' => $visibleShips,
                            'source' => 'notification'
                        ]);
                        print "Fleet movement saved.\n";
                    }

                    // Request scans @todo

                    // Message TG chat with new incoming @todo
                    $chatId = Setting::where('name', 'tg_notifications_channel')->first();

                    if($chatId->value) {
                        $data = [
                            'chat_id' => $chatId->value,
                            'text'    => "New incoming reported for " . $user->name . " (" . $planetTo->x . ":" . $planetTo->y . ":" . $planetTo->z . ") from " . $planetFrom->x . ":" . $planetFrom->y . ":" . $planetFrom->z . " eta " . $eta
                        ];

                        $telegram = new Telegram(Config::get('phptelegrambot.bot.api_key'), Config::get('phptelegrambot.bot.name'));
                        Request::sendMessage($data);
                    }
                }
            }

            // Message user the notification @todo
            if($user->tg_username) {
                $data = [
                    'chat_id' => $user->tg_username,
                    'text'    => $body,
                ];
    
                $telegram = new Telegram(Config::get('phptelegrambot.bot.api_key'), Config::get('phptelegrambot.bot.name'));
                Request::sendMessage($data);
            }

            $mailbox->markMailAsRead($id);
        };

        $mailbox->disconnect();
    }
}
