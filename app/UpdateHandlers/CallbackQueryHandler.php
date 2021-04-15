<?php

namespace FireKeeper\UpdateHandlers;

use WeStacks\TeleBot\Interfaces\UpdateHandler;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;
use Illuminate\Support\Facades\Config;
use FireKeeper\Http\Controllers\TelegramUserController;

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

        $controller = new TelegramUserController;
        $user = $controller->getUserFromUpdate($this->update);

        $done = false;
        if (str_starts_with($query->data, $callbackTypes['language'])) {
            $user->locale = str_replace($callbackTypes['language'], '', $query->data);
            $done = $user->save();
        } elseif (str_starts_with($query->data, $callbackTypes['alias'])) {
            $user->alias = str_replace($callbackTypes['alias'], '', $query->data);
            $done = $user->save();
        }

        // If some task has been done (successfully), send message
        if ($done) {
            $this->sendMessage([
                'text' => __(
                    'bot_messages.success',
                    ['alias' => __("bot_messages.$user->alias", [], $user->locale)],
                    $user->locale
                ),
            ]);
        }

        $this->answerCallbackQuery([]);
    }
}
