<?php

namespace FireKeeper\Commands;

use FireKeeper\Http\Controllers\UserController;
use WeStacks\TeleBot\Handlers\CommandHandler;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class ChangeAliasCommand extends CommandHandler
{
    protected static $aliases = ['/change_alias', '/cambiar_alias', '/c_alias'];
    protected static $description = 'Change the way the Fire Keeper calls you.';

    public function handle()
    {
        $user = (new UserController)->getByTelgramId($this->update->message->from->id);
        $replyOptions = [
            'inline_keyboard' => []
        ];

        $userAliases = Config::get('constants.aliases');
        foreach ($userAliases as $alias) {
            $replyOptions['inline_keyboard'][] = [
                'text' => __("bot_messages.$alias", [], $user->locale),
                'callback_data' => "alias:$alias",
            ];
        }

        $this->sendMessage([
            'text' => __('bot_messages.change_alias', [], $user->locale),
            'reply_markup' => json_encode($replyOptions)
        ]);
    }
}