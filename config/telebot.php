<?php

return [
    'default' => 'keeper',
    'bots' => [
        'keeper' => [
            'token' => env('TELEGRAM_BOT_TOKEN'),
            'name' => env('TELEGRAM_BOT_NAME', null),
            'api_url' => env('TELEGRAM_BOT_API_URL'),
            'exceptions' => true,
            'async' => false,
            'handlers' => [
                FireKeeper\Commands\StartCommand::class,
                FireKeeper\Commands\ChangeAliasCommand::class,
                FireKeeper\Commands\ChangeLanguageCommand::class,
                FireKeeper\Commands\DonateCommand::class,
            ],
            'webhook' => [],
            'poll' => [],

        ],
    ],
];
