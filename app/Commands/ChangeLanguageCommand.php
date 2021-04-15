<?php

namespace FireKeeper\Commands;

use WeStacks\TeleBot\Handlers\CommandHandler;
use FireKeeper\Http\Controllers\TelegramUserController;
use Illuminate\Support\Facades\Config;
use WeStacks\TeleBot\Objects\InlineKeyboardButton;
use WeStacks\TeleBot\Objects\Keyboard;

class ChangeLanguageCommand extends CommandHandler
{
    protected static $aliases = ['/language', '/idioma'];
    protected static $description = 'Change the language in what the bot speaks.';

    public function handle()
    {
        $user = (new TelegramUserController)->getUserFromUpdate($this->update);
        $replyOptions = [
            'inline_keyboard' => []
        ];

        $supportedLanguages = Config::get('constants.supported_languages');
        foreach ($supportedLanguages as $code => $language) {
            $replyOptions['inline_keyboard'][] = [
                new InlineKeyboardButton([
                    'text' => $language,
                    'callback_data' => "language:$code",
                ])
            ];
        }

        $this->sendMessage([
            'text' => __('bot_messages.change_language', [], $user->locale),
            'reply_markup' => Keyboard::create($replyOptions)
        ]);
    }
}
