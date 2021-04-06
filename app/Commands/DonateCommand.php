<?php

namespace FireKeeper\Commands;

use WeStacks\TeleBot\Handlers\CommandHandler;
use FireKeeper\Http\Controllers\TelegramUserController;

class DonateCommand extends CommandHandler
{
    protected static $aliases = ['/donate', '/donar'];
    protected static $description = 'Donate if you desire to.';

    public function handle()
    {
        $user = (new TelegramUserController)->getByTelgramId($this->update->message->from->id);

        $this->sendMessage([
            'text' => __('bot_messages.donate', ['donate_url' => env('DONATE_BUYMEACOFFEE')], $user->locale)
        ]);
    }
}
