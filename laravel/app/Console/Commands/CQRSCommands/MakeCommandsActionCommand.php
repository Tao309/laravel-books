<?php

namespace App\Console\Commands\CQRSCommands;

use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'make:cqrs:command', description: 'Create a CQRS Command classes.')]
class MakeCommandsActionCommand extends AbstractCQRSActionCommand
{
    protected $signature = 'make:cqrs:command {name} {action}';

    protected $description = 'Create a new CQRS Command classes';

    protected $type = 'CQRS Command';

    public function handle(): bool
    {
        return $this->handleActionCommand('command');
    }
}
