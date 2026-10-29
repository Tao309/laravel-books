<?php

namespace App\Console\Commands\CQRSCommands;

use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'make:cqrs:query', description: 'Create a CQRS Query classes.')]
class MakeQueriesActionCommand extends AbstractCQRSActionCommand
{
    protected $signature = 'make:cqrs:query {name} {action}';

    protected $description = 'Create a new CQRS Query classes';

    protected $type = 'CQRS Handler';

    public function handle()
    {
        return $this->handleActionCommand('query');
    }
}
