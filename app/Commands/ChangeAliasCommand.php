<?php

namespace FireKeeper\Commands;

use WeStacks\TeleBot\Handlers\CommandHandler;
use FireKeeper\Http\Controllers\TelegramUserController;
use Illuminate\Support\Facades\Config;
use WeStacks\TeleBot\Objects\InlineKeyboardButton;
use WeStacks\TeleBot\Objects\Keyboard;

class ChangeAliasCommand extends CommandHandler
{
    protected static $aliases = ['/alias'];
    protected static $description = 'Change the way the Fire Keeper calls you.';

    public function handle()
    {
        $user = (new TelegramUserController)->getUserFromUpdate($this->update);
        $replyOptions = [
            'inline_keyboard' => []
        ];

        $userAliases = Config::get('constants.aliases');
        foreach ($userAliases as $alias) {
            $replyOptions['inline_keyboard'][] = [
                new InlineKeyboardButton([
                    'text' => __("bot_messages.$alias", [], $user->locale),
                    'callback_data' => "alias:$alias",
                ])
            ];
        }

        $this->sendMessage([
            'text' => __(
                'bot_messages.change_alias',
                ['alias' => __("bot_messages.$user->alias", [], $user->locale)],
                $user->locale
            ),
            'reply_markup' => Keyboard::create($replyOptions)
        ]);
    }
}
