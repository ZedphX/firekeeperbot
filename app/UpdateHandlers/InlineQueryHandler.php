<?php

namespace FireKeeper\UpdateHandlers;

use WeStacks\TeleBot\Interfaces\UpdateHandler;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;
use Illuminate\Support\Facades\Config;
use FireKeeper\Http\Controllers\TelegramUserController;
use FireKeeper\Http\Controllers\ReminderController;

class InlineQueryHandler extends UpdateHandler
{
    public static function trigger(Update $update, TeleBot $bot): bool
    {
        return isset($update->inline_query);
    }

    public function handle()
    {
        $query = $this->update->inline_query;
        $inlineCommands = Config::get('constants.inline_commands');

        if ($this->isCommand($query->query, $inlineCommands['remind'])) {
            $user = (new TelegramUserController)->getByTelgramId($query->from->id);
            $updateMessage = $this->removeCommand($query->query, $inlineCommands['remind']);

            (new ReminderController)->setReminder($user->telegram_id, $updateMessage, $user->alias, $user->locale);
        }
        $this->answerInlineQuery([]);
    }

    /**
     * Checks if query starts with a command alias
     */
    private function isCommand(string $query, array $commandAliases): bool
    {
        return preg_match("/^" . implode("|", $commandAliases) . "/", $query);
    }

    /**
     * Remove command alias
     */
    private function removeCommand(string $query, array $commandAliases): bool
    {
        return preg_replace("/^" . implode("|", $commandAliases['remind']) . "/", '', $query, 1);
    }
}
