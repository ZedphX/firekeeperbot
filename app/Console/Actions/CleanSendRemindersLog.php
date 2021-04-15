<?php

namespace FireKeeper\Console\Actions;

class CleanSendRemindersLog
{
    public function __invoke()
    {
        // Empty log file
        file_put_contents(storage_path('logs/send_reminders.log'), '');
    }
}
