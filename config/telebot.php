<?php

return [
    'default' => 'keeper',
    'bots' => [
        'keeper' => [
            'token' => env('TELEGRAM_BOT_TOKEN'),
            'name' => env('TELEGRAM_BOT_USERNAME', null),
            'api_url' => env('TELEGRAM_BOT_API_URL'),
            'exceptions' => true,
            'async' => false,
            'handlers' => [
                #### Command Handlers #####
                FireKeeper\Commands\StartCommand::class,
                \FireKeeper\Commands\RemindMeCommand::class,
                FireKeeper\Commands\ChangeAliasCommand::class,
                FireKeeper\Commands\ChangeLanguageCommand::class,
                FireKeeper\Commands\DonateCommand::class,
                FireKeeper\Commands\DiceCommand::class,
                #### Other Handlers #####
                FireKeeper\UpdateHandlers\CallbackQueryHandler::class,
                //FireKeeper\UpdateHandlers\InlineQueryHandler::class,
            ],
            'webhook' => [],
            'poll' => [],

        ],
    ],
];
