<?php

namespace FireKeeper\Http\Controllers\Commands;

use WeStacks\TeleBot\Handlers\CommandHandler;

class StartCommand extends CommandHandler
{
    protected static $aliases = [ '/start', '/s' ];
    protected static $description = 'Send "/start" or "/s" to get "Hello, World!"';

    public function handle()
    {
        //check if user exists in db, else use default alias
        $default_alias = __('bot_messages.unkindled');
        $message = \str_replace(':alias', $default_alias, __('bot_messages.welcome'));        
        $this->sendMessage([
            'text' => $message
        ]);
    }
}