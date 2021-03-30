<?php

namespace FireKeeper\UpdateHanlders;

use Illuminate\Support\Facades\Config;
use WeStacks\TeleBot\Interfaces\UpdateHandler;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;

class CallbackQueryHandler extends UpdateHandler
{
    public static function trigger(Update $update, TeleBot $bot): bool
    {
        return isset($update->callback_query);
    }

    public function handle()
    {
        //TODO add answer notification?
        $query = $this->update->callback_query;
        $callbackTypes = Config::get('constants.callback_query_types');

        $user = (new UserController)->getByTelgramId($query->from->id);

        if (str_starts_with($query->data, $callbackTypes['language'])) {
            $user->locale = str_replace($callbackTypes['language'], '', $query->data);
        } elseif (str_starts_with($query->data, $callbackTypes['alias'])) {
            $user->alias = str_replace($callbackTypes['alias'], '', $query->data);
        }

        $this->answerCallbackQuery([]);
    }
}
