<?php

namespace FireKeeper\Services;

use FireKeeper\Models\Reminder;
use Carbon\Carbon;
use FireKeeper\Jobs\SendReminderProcess;

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
    public function get($id)
    {
        $reminder = Reminder::find($id);
        return $reminder;
    }

    /**
     * Sends all reminders which remind_date is equal or less 
     * than the moment this function is executed.
     * 
     * @param int $limit
     * @return boolean
     */
    public function sendReminders($limit)
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
    private function validateMessage($message)
    {
        //TODO make this a service

        /**
         * Expected format: text_to_remind on/in date/time_expression
         * text_to_remind not empty
         */

        $possibleDelimiters = [
            ' on ', //Real date. Example: on March 22nd
            ' in ' //Time expression. Example: in 20 minutes
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
            $remindDate = $delimiter == $possibleDelimiters[0] ? strtotime($arguments[0]) : strtotime("+$arguments[0]");

            if ($remindDate) {
                return [
                    'valid' => true,
                    'text_to_remind' => $arguments[1],
                    'remind_date' => $remindDate,
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
