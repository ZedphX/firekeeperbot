<?php

namespace FireKeeper\Commands;

use WeStacks\TeleBot\Handlers\CommandHandler;
use FireKeeper\Http\Controllers\TelegramUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class StartCommand extends CommandHandler
{
    protected static $aliases = ['/start'];
    protected static $description = 'Start a conversation with the Fire Keeper.';

    public function handle()
    {
        $user = (new TelegramUserController)->getUserFromUpdate($this->update);

        $this->sendMessage([
            'text' => __(
                'bot_messages.welcome',
                ['alias' => __("bot_messages.$user->alias", [], $user->locale)],
                $user->locale
            )
        ]);
    }
}
