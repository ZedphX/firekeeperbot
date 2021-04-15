<?php

namespace FireKeeper\Commands;

use WeStacks\TeleBot\Handlers\CommandHandler;
use FireKeeper\Http\Controllers\ReminderController;
use FireKeeper\Http\Controllers\TelegramUserController;

class RemindMeCommand extends CommandHandler
{
    protected static $aliases = ['/remindme', '/recuerdame', '/r'];
    protected static $description = 'Ask the Fire Keeper to remind you something.';

    public function handle()
    {
        $user = (new TelegramUserController)->getUserFromUpdate($this->update);
        $updateMessage = preg_replace("/^" . implode("|", preg_filter('/^/', "\\", RemindMeCommand::$aliases)) . "/", '', $this->update->message->text, 1);

        $result = (new ReminderController)->setReminder($user->telegram_id, $updateMessage, $user->alias, $user->locale);
    }
}
