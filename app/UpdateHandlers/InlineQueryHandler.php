<?php

namespace FireKeeper\UpdateHanlders;

use FireKeeper\Commands\RemindMeCommand;
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
        $query = $this->update->inline_query->query;
        $inlineCommands = Config::get('constants.inline_commands');

        if ($this->isCommand($query, $inlineCommands['remind'])) {
            $remindMe = new RemindMeCommand($this->bot, $this->update);
            //$remindMe->handle();
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
}
