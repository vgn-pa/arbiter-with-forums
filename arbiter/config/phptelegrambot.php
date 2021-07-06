<?php

declare(strict_types=1);

return [
    /**
     * Bot configuration
     */
    'bot'      => [
        'name'    => env('PHP_TELEGRAM_BOT_NAME', ''),
        'api_key' => env('PHP_TELEGRAM_BOT_API_KEY', ''),
    ],

    /**
     * Database integration
     */
    'database' => [
        'enabled'    => true,
        'connection' => env('DB_CONNECTION', 'mysql'),
    ],

    'commands' => [
        'before'  => true,
        'paths'   => [
            base_path('/app/Telegram/Commands')
        ],
        'configs' => [
            
        ],
        'map' => [
            'attacks' => 'App\Telegram\Custom\AttacksCommand',
            'cost' => 'App\Telegram\Custom\CostCommand',
            'eff' => 'App\Telegram\Custom\EffCommand',
            'help' => 'App\Telegram\Custom\HelpCommand',
            'intel' => 'App\Telegram\Custom\IntelCommand',
            'lookup' => 'App\Telegram\Custom\LookupCommand',
            'req' => 'App\Telegram\Custom\ReqCommand',
            'reqs' => 'App\Telegram\Custom\ReqsCommand',
            'roidcost' => 'App\Telegram\Custom\RoidcostCommand',
            'setnick' => 'App\Telegram\Custom\SetnickCommand',
            'setplanet' => 'App\Telegram\Custom\SetplanetCommand',
            'ship' => 'App\Telegram\Custom\ShipCommand',
            'spam'      => 'App\Telegram\Custom\SpamCommand',
            'stop' => 'App\Telegram\Custom\StopCommand',
            'tools' => 'App\Telegram\Custom\ToolsCommand',
	    'tick' => 'App\Telegram\Custom\TickCommand',
	    'titpics' => 'App\Telegram\Custom\TitpicsCommand',
	    'whorules' => 'App\Telegram\Custom\WhorulesCommand',
		'rob' => 'App\Telegram\Custom\RobCommand',
		'whodidthis' => 'App\Telegram\Custom\WhodidthisCommand',
        'rick' => 'App\Telegram\Custom\RickCommand',
        'doris' => 'App\Telegram\Custom\DorisCommand',
        ]
    ],

    'admins'  => [
        env('TG_ADMIN_ID', '')
    ],

    /**
     * Request limiter
     */
    'limiter' => [
        'enabled'  => false,
        'interval' => 1,
    ],

    'upload_path'   => '',
    'download_path' => '',
];
