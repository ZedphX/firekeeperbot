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
        $update = $this->update->message;

        $request = new \Illuminate\Http\Request(
            isset($update->inline_query) ? [
                'user_telegram_id' => $update->inline_query->from->id,
                'message' => $update->inline_query->query,
            ] : [
                'user_telegram_id' => $update->message->from->id,
                'message' => $update->message->text,
            ]
        );

        $controller = new ReminderController;
        $result = $controller->store($request);

        if ($result && is_numeric($result)) {
            $reminder = $controller->get($result);
            $timeLeft = Carbon::now()->locale($user->locale)->diffForHumans($reminder->remind_date);

            $message = __('bot_messages.reminder_add', [
                'text_to_remind' => $reminder->text_to_remind,
                'time_left' => $timeLeft
            ], $user->locale);
        } else $message = __('bot_messages.error', ['alias' => $user->alias], $user->locale);

        $this->sendMessage([
            'text' => $message
        ]);
    }
}
