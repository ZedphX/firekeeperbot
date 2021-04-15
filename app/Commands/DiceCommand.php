<?php

namespace FireKeeper\Commands;

use WeStacks\TeleBot\Handlers\CommandHandler;

class DiceCommand extends CommandHandler
{
    protected static $aliases = ['/dice', '/dado'];
    protected static $description = 'Throw a dice and shows the result.';

    public function handle()
    {
        $this->sendDice([]);
    }
}
