<?php

namespace App\Console\Commands\CQRSCommands;

use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'make:cqrs:handler', description: 'Create a CQRS Handler classes.')]
class MakeHandlersActionCommand extends AbstractCQRSActionCommand
{
    protected $signature = 'make:cqrs:handler {name} {action}';

    protected $description = 'Create a new CQRS Handler classes';

    protected $type = 'CQRS Handler';

    public function handle()
    {
        return $this->handleActionCommand('handler');
    }
}
