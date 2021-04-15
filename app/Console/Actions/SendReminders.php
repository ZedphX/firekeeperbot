<?php

namespace FireKeeper\Console\Actions;

use FireKeeper\Http\Controllers\ReminderController;
use Illuminate\Support\Facades\Log;

class SendReminders
{
    public function __invoke()
    {
        $date = date('Y-m-d H:i:s');
        try {
            Log::channel('send_reminders')->info("$date - Starting...");
            (new ReminderController)->sendReminders();
            Log::channel('send_reminders')->info("$date - Done");
        } catch (Exception $e) {
            Log::channel('send_reminders')->error("$date - Error: {$e->getMessage()}");
        }
    }
}
