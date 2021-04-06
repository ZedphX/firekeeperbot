<?php

namespace FireKeeper\Commands;

use WeStacks\TeleBot\Handlers\CommandHandler;
use FireKeeper\Http\Controllers\TelegramUserController;

class ChangeLanguageCommand extends CommandHandler
{
    protected static $aliases = ['/language', '/idioma'];
    protected static $description = 'Change the language in what the bot speaks.';

    public function handle()
    {
        $user = (new TelegramUserController)->getByTelgramId($this->update->message->from->id);
        $replyOptions = [
            'inline_keyboard' => []
        ];

        $supportedLanguages = Config::get('constants.supported_languages');
        foreach ($supportedLanguages as $code => $language) {
            $replyOptions['inline_keyboard'][] = [
                'text' => $language,
                'callback_data' => "language:$code",
            ];
        }

        $this->sendMessage([
            'text' => __('bot_messages.change_language', [], $user->locale),
            'reply_markup' => json_encode($replyOptions)
        ]);
    }
}
