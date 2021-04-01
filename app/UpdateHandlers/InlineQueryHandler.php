<?php

namespace FireKeeper\UpdateHanlders;

use WeStacks\TeleBot\Interfaces\UpdateHandler;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;

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
            $user = (new UserController)->getByTelgramId($query->from->id);
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
        return preg_match("/^" . implode("|", $commandAliases['remind']) . "/", $query);
    }

    /**
     * Remove command alias
     */
    private function removeCommand(string $query, array $commandAliases): bool
    {
        return preg_replace("/^" . implode("|", $commandAliases['remind']) . "/", '', $query, 1);
    }
}
