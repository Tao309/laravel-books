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

#[AsCommand(name: 'app:make:cqrs')]
class MakeCQRSCommand extends GeneratorCommand
{
    protected $signature = 'app:make:cqrs {name}';

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
        $pluralName = Str::plural($this->getNameInput());

        $this->call('app:make:model', [
            'name' => $upperName,
        ]);

        $this->call('make:migration', [
            'name' => 'Create'.$pluralName.'Table',
            '--table' => Str::snake($pluralName),
            '--create' => true,
        ]);

        $this->call('make:seed', ['name' => $upperName.'Seeder']);

        $this->call('app:make:request', [
            'name' => $upperName,
            '--abstract' => true,
        ]);

        $this->call('app:make:request', [
            'name' => $upperName,
            '--store' => true,
        ]);

        $this->call('app:make:request', [
            'name' => $upperName,
            '--update' => true,
        ]);

        $this->call('app:make:controller', [
            'name' => $upperName,
        ]);

        $this->call('make:request', [
            'name' => $pluralName.'/Store'.$upperName.'Request',
        ]);

        $this->call('make:request', [
            'name' => $pluralName.'/Update'.$upperName.'Request',
        ]);

        $this->call('app:make:resource', [
            'name' => $upperName,
        ]);

        $this->call('app:make:resource', [
            'name' => $upperName,
            '--data' => true,
        ]);

        $this->call('app:make:resource', [
            'name' => $upperName,
            '--collection' => true,
        ]);

        $this->call('app:make:resource', [
            'name' => $upperName,
            '--collection' => true,
            '--data' => true,
        ]);

        $this->call('make:provider', [
            'name' => Str::singular($upperName).'Provider',
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
        $this->call('app:make:dto', [
            'name' => $this->getNameInput(),
        ]);
    }

    private function createRepository(): void
    {
        $this->call('app:make:repository', [
            'name' => $this->getNameInput(),
            '--force' => true,
        ]);
    }

    public function createService(): void
    {
        $this->call('app:make:service', [
            'name' => $this->getNameInput(),
            '--force' => true,
        ]);
    }

    private function createCommands(): void
    {
        $name = $this->getNameInput();

        foreach (['create', 'destroy', 'update'] as $action) {
            $this->call('app:make:cqrs:command', [
                'name' => $name,
                'action' => $action,
            ]);
        }
    }

    private function createHandlers(): void
    {
        $name = $this->getNameInput();

        foreach (['abstract', 'create', 'destroy', 'get', 'getById', 'update'] as $action) {
            $this->call('app:make:cqrs:handler', [
                'name' => $name,
                'action' => $action,
            ]);
        }
    }

    private function createQueries(): void
    {
        $name = $this->getNameInput();

        foreach (['get', 'getById'] as $action) {
            $this->call('app:make:cqrs:query', [
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
