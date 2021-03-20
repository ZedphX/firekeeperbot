<?php

namespace FireKeeper\Http\Controllers\Commands;

use WeStacks\TeleBot\Handlers\CommandHandler;

class ChangeAliasCommand extends CommandHandler
{
    protected static $aliases = [ '/change_alias', '/cambiar_alias', '/c_alias' ];
    protected static $description = '';

    public function handle()
    {
        $user_message = $this->update->message();
        $user_selection = '';
        App::setLocale($user_selection);
        $this->sendMessage([
            'text' => ''
        ]);
    }
}