<?php

namespace FireKeeper\Commands;

use WeStacks\TeleBot\Handlers\CommandHandler;
use FireKeeper\Http\Controllers\ReminderController;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;

class RemindMeCommand extends CommandHandler
{
    protected static $aliases = ['/remindme', '/recuerdame', '/r'];
    protected static $description = 'Ask the Fire Keeper to remind you something.';

    public function handle()
    {
        $user = (new UserController)->getByTelgramId($this->update->message->from->id);
        $updateMessage = preg_replace("/^" . implode("|", $this->aliases) . "/", '', $this->update->message, 1);

        $result = (new ReminderController)->setReminder($user->telegram_id, $updateMessage, $user->alias, $user->locale);
    }
}
