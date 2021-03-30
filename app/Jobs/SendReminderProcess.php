<?php

namespace FireKeeper\Jobs;

use FireKeeper\Models\Reminder;
use FireKeeper\Services\UserService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendReminderProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The reminder instance.
     *
     * @var \FireKeeper\Models\Reminder
     */
    protected $reminder;

    /**
     * Create a new job instance.
     *
     * @param  \FireKeeper\Models\Reminder  $reminder
     * @return void
     */
    public function __construct(Reminder $reminder)
    {
        $this->reminder = $reminder;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $reminder_user = (new UserService)->getByTelgramId($this->reminder->user_telegram_id);

        $message = str_replace(':alias', $reminder_user->alias, __('bot_messages.remind_send'));
        $message = str_replace(':text_to_remind', $this->reminder->text_to_remind, $message);

        $result = TeleBot::async()->sendMessage([
            'chat_id' => $this->reminder->user_telegram_id,
            'text' => $message
        ]);

        if ($result) $this->reminder->delete();
    }
}
