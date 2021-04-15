<?php

namespace FireKeeper\Services;

use FireKeeper\Models\Reminder;
use WeStacks\TeleBot\Laravel\TeleBot;
use Carbon\Carbon;
use FireKeeper\Jobs\SendReminderProcess;
use Illuminate\Support\Facades\Config;

class ReminderService
{

    /**
     * Creates a new Reminder and store it in the database.
     * Returns reminder's id or false.
     * 
     * @param array $data
     * @return int|false
     */
    public function create($data)
    {
        $reminder = new Reminder;
        $reminder->user_telegram_id = $data['user_telegram_id'];
        //$reminder->chat_id = $data['chat_id'];
        $reminder->message = $data['message'];

        $validateData = $this->validateMessage($reminder->message);
        if ($validateData && $validateData['valid']) {
            $reminder->text_to_remind = $validateData['text_to_remind'];
            $reminder->remind_date = $validateData['remind_date'];

            return $reminder->save() ? $reminder->id : false;
        }

        return false;
    }

    /**
     * Deletes a Reminder from the database.
     * 
     * @param int $id
     * @return int|false
     */
    public function delete($id)
    {
        $result = Reminder::find($id)->delete();
        return $result;
    }

    /**
     * Get a Reminder from the database.
     * 
     * @param int $id
     * @return Reminder
     */
    public function get(int $id)
    {
        $reminder = Reminder::find($id);
        return $reminder;
    }

    /**
     * Set a Reminder.
     * Returns an array with the status (success/error) and a response to the user.
     * 
     * @param int $userTelegramId
     * @param string $message
     * @param string $alias
     * @param string $locale
     * @return array
     */
    public function setReminder(int $userTelegramId, string $message, string $alias = '', string $locale = 'en')
    {
        if (!$alias) $alias = Config::get('constants.default_alias');

        $result = $this->create([
            'user_telegram_id' => $userTelegramId,
            'message' => $message,
        ]);

        if ($result && is_numeric($result)) {
            $reminder = $this->get($result);
            $timeLeft = Carbon::now()->locale($locale)->diffForHumans($reminder->remind_date);

            $status = Config::get('constants.statuses.success');
            $responseMessage = __('bot_messages.remind_add', [
                'text_to_remind' => $reminder->text_to_remind,
                'time_left' => $timeLeft
            ], $locale);
        } else {
            $status = Config::get('constants.statuses.error');
            $responseMessage = __('bot_messages.error', ['alias' => __("bot_messages.$alias", [], $locale)], $locale);
        }

        TeleBot::sendMessage([
            'chat_id' => $userTelegramId,
            'text' => $responseMessage
        ]);

        return [
            'status' => $status,
            'message' => $responseMessage
        ];
    }

    /**
     * Sends all reminders which remind_date is equal or less 
     * than the moment this function is executed.
     * 
     * @param int $limit
     * @return boolean
     */
    public function sendReminders(int $limit)
    {
        $toBeSend = Reminder::whereDate('remind_date', '<=', Carbon::now())->limit($limit)->get();
        foreach ($toBeSend as $reminder) {
            SendReminderProcess::dispatch($reminder);
        }
    }

    /**
     * Validates a message to check if it format is correct.
     * If it is correct, returns valid, text to remind and remind date,
     * else returns valid = false.
     * 
     * @param string $message
     * @return array
     */
    private function validateMessage(string $message)
    {
        //TODO make this a service

        /**
         * Expected format: text_to_remind on/in date/time_expression
         * text_to_remind not empty
         */

        $possibleDelimiters = [
            ...Config::get('constants.reminder_delimiters.date'),
            ...Config::get('constants.reminder_delimiters.time')
        ];

        $delimitersPos = [];

        // Search last position of delimiters
        for ($i = 0; $i < count($possibleDelimiters); $i++) {
            $lastPos = strrpos($message, $possibleDelimiters[$i]);
            if ($lastPos) $delimitersPos[$possibleDelimiters[$i]] = $lastPos;
        }

        if ($delimitersPos) {
            arsort($delimitersPos);
            $delimiter = array_key_first($delimitersPos);

            $arguments = array_map('strrev', explode(strrev($delimiter), strrev($message), 2));
            if (in_array($delimiter, Config::get('constants.reminder_delimiters.date'))) {
                $remindDate = CarCarbon::createFromTimestamp(strtotime($arguments[0]))->toDateTimeString();;
            } else {
                //TODO locale $argumentParts[0] -> days, months, etc
                $timeAmount = (int) preg_replace('/[^0-9]/', '', $arguments[0]);
                $timeUnit = preg_replace('/[^a-zA-Z]/', '', $arguments[0]);

                $remindDate = Carbon::now()->add($timeUnit, $timeAmount);
            }

            if ($remindDate && $remindDate instanceof Carbon) {
                return [
                    'valid' => true,
                    'text_to_remind' => $arguments[1],
                    'remind_date' => $remindDate->format('Y-m-d H:i:s'),
                ];
            }
        }

        return [
            'valid' => false,
            'text_to_remind' => '',
            'remind_date' => '',
        ];
    }
}
