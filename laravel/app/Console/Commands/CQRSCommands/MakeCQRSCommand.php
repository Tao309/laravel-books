<?php

namespace App\Console\Commands\CQRSCommands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\suggest;

#[AsCommand(name: 'make:cqrs')]
class MakeCQRSCommand extends GeneratorCommand
{
    protected $signature = 'make:cqrs {name}';

    protected $description = 'Create a new CQRS classes';

    protected $type = 'CQRS';

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the '.strtolower($this->type)],
        ];
    }

    public function handle()
    {
        if (! confirm('Do you want to generate CQRS components?')) {
            return false;
        }

        if ($this->isReservedName($this->getNameInput())) {
            $this->components->error('The name "'.$this->getNameInput().'" is reserved by PHP.');

            return false;
        }

        $upperName = Str::ucfirst($this->getNameInput());

        $this->call('make:model', [
            'name' => $upperName,
            '--migration' => true,
            '--seed' => true,
            '--requests' => true,
        ]);

        $this->call('make:controller', [
            'name' => 'Api/'.$upperName.'Controller',
            '--model' => $upperName,
            '--requests' => true,
        ]);

        $this->call('make:resource', [
            'name' => Str::plural($upperName).'/'.$upperName.'Resource',
        ]);

        $this->call('make:resource', [
            'name' => Str::plural($upperName).'/'.$upperName.'DataResource',
        ]);

        $this->call('make:resource', [
            'name' => Str::plural($upperName).'/'.$upperName.'CollectionResource',
            '--collection' => true,
        ]);

        $this->createDTO();
        $this->createRepository();
        $this->createService();
        $this->createCommands();
        $this->createHandlers();
        $this->createQueries();

        return true;
    }

    private function createDTO(): void
    {
        $this->call('make:dto', [
            'name' => $this->getNameInput(),
        ]);
    }

    private function createRepository(): void
    {
        $this->call('make:repository', [
            'name' => $this->getNameInput(),
            '--force' => true,
        ]);
    }

    public function createService(): void
    {
        $this->call('make:service', [
            'name' => $this->getNameInput(),
            '--force' => true,
        ]);
    }

    private function createCommands(): void
    {
        $name = $this->getNameInput();

        foreach (['create', 'destroy', 'update'] as $action) {
            $this->call('make:cqrs:command', [
                'name' => $name,
                'action' => $action,
            ]);
        }
    }

    private function createHandlers(): void
    {
        $name = $this->getNameInput();

        foreach (['abstract', 'create', 'destroy', 'get', 'getById', 'update'] as $action) {
            $this->call('make:cqrs:handler', [
                'name' => $name,
                'action' => $action,
            ]);
        }
    }

    private function createQueries(): void
    {
        $name = $this->getNameInput();

        foreach (['get', 'getById'] as $action) {
            $this->call('make:cqrs:query', [
                'name' => $name,
                'action' => $action,
            ]);
        }
    }

    protected function getStub()
    {
        return null;
    }

    protected function afterPromptingForMissingArguments(InputInterface $input, OutputInterface $output)
    {
        if ($this->isReservedName($this->getNameInput()) || $this->didReceiveOptions($input)) {
            return;
        }

        $model = suggest(
            'What model should this CQRS apply to? (Optional)',
            $this->findAvailableModels(),
        );

        if ($model) {
            $input->setOption('model', $model);
        }
    }
}
