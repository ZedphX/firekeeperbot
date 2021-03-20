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
                FireKeeper\Http\Controllers\Commands\StartCommand,
                FireKeeper\Http\Controllers\Commands\ChangeAliasCommand
            ],
            'webhook' => [
                // 'url'               => env('TELEGRAM_BOT_WEBHOOK_URL', env('APP_URL').'/telebot/webhook/bot/'.env('TELEGRAM_BOT_TOKEN')),,
                // 'certificate'       => env('TELEGRAM_BOT_CERT_PATH', storage_path('app/ssl/public.pem')),
                // 'ip_address'        => '8.8.8.8',
                // 'max_connections'   => 40,
                // 'allowed_updates'   => ["message", "edited_channel_post", "callback_query"]
            ],
            'poll' => [
                // 'limit'             => 100,
                // 'timeout'           => 0,
                // 'allowed_updates'   => ["message", "edited_channel_post", "callback_query"]
            ],

        ],
    ],
];
