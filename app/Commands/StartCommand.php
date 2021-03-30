<?php

namespace FireKeeper\Commands;

use Exception;
use WeStacks\TeleBot\Handlers\CommandHandler;
use FireKeeper\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class StartCommand extends CommandHandler
{
    protected static $aliases = ['/start', '/s'];
    protected static $description = 'Start a conversation with the Fire Keeper.';

    public function handle()
    {
        try {
            $controller = new UserController;
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
                    'name' => "$messageUser->first_name $messageUser->last_name"
                ]));
            }

            $this->sendMessage([
                'text' => __('bot_messages.welcome', ['alias' => $user->alias])
            ]);
        } catch (Exception $e) {
            echo $e->getMessage();
            file_put_contents(__DIR__ . '/commands.log', $e->getMessage(), FILE_APPEND);
        }
    }
}
