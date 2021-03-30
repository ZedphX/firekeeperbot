<?php

namespace FireKeeper\Console\Actions;

use FireKeeper\Http\Controllers\ReminderController;

class SendReminders
{
    public function __invoke()
    {
        $controller = new ReminderController();
        $controller->sendReminders();
    }
}
