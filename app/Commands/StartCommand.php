<?php

namespace FireKeeper\Commands;

use Exception;
use WeStacks\TeleBot\Handlers\CommandHandler;
use FireKeeper\Http\Controllers\TelegramUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class StartCommand extends CommandHandler
{
    protected static $aliases = ['/start', '/s'];
    protected static $description = 'Start a conversation with the Fire Keeper.';

    public function handle()
    {
        $controller = new TelegramUserController;
        $messageUser = $this->update->message->from;

        //TODO User::firstOrNew
        $user = $controller->getByTelgramId($messageUser->id);
        if (!$user) {
            $defaultAlias = Config::get('constants.default_alias');
            $languageCode = substr($messageUser->language_code, 0, 2);

            $controller->store(new Request([
                'telegram_id' => $messageUser->id,
                'alias' => __("bot_messages.$defaultAlias"),
                'locale' => in_array($languageCode, array_keys(Config::get('constants.supported_languages')))
                    ? $languageCode : 'en',
            ]));
        }

        $this->sendMessage([
            'text' => __('bot_messages.welcome', ['alias' => $user->alias])
        ]);
    }
}
