<?php

namespace App\Console\Commands\CQRSCommands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(name: 'make:service', description: 'Create a Service class.')]
class MakeServiceCommand extends GeneratorCommand
{
    protected $signature = 'make:service {name} {--f|force}';

    protected $description = 'Create a new Service class';

    protected $type = 'Service';

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the '.strtolower($this->type)],
        ];
    }

    public function handle()
    {
        if ($this->isReservedName($this->getNameInput())) {
            $this->components->error('The name "'.$this->getNameInput().'" is reserved by PHP.');

            return false;
        }

        $modelClass = '\\App\\Models\\'.$this->getNameInput();

        if (! class_exists($modelClass) && ! ($this->hasOption('force') && $this->option('force'))) {
            $this->components->error('Required model class: '.$modelClass);

            return false;
        }

        return parent::handle();
    }

    protected function replaceNamespace(&$stub, $name)
    {
        parent::replaceNamespace($stub, $name);

        $singularName = Str::singular($this->getNameInput());
        $pluralName = Str::plural($this->getNameInput());

        $searches = [
            [
                '{{ ModelName }}',
                '{{ GetModelsQueryName }}',
                '{{ GetModelsQueryNamespace }}',
                '{{ GetByIdModelQueryName }}',
                '{{ GetByIdModelQueryNamespace }}',
                '{{ CreateModelCommandName }}',
                '{{ CreateModelCommandNamespace }}',
                '{{ UpdateModelCommandName }}',
                '{{ UpdateModelCommandNamespace }}',
            ],
        ];

        foreach ($searches as $search) {
            $stub = str_replace(
                $search,
                [
                    $singularName,
                    'Get'.$pluralName.'Query',
                    'App\\CQRS\\Queries\\'.$pluralName.'\\Get'.$pluralName.'Query',
                    'GetById'.$singularName.'Query',
                    'App\\CQRS\\Queries\\'.$pluralName.'\\GetById'.$singularName.'Query',
                    'Create'.$singularName.'Command',
                    'App\\CQRS\\Commands\\'.$pluralName.'\\Create'.$singularName.'Command',
                    'Update'.$singularName.'Command',
                    'App\\CQRS\\Commands\\'.$pluralName.'\\Update'.$singularName.'Command',
                ],
                $stub
            );
        }

        return $this;
    }

    protected function qualifyClass($name)
    {
        $name = ltrim($name, '\\/');

        $name = str_replace('/', '\\', $name).'Service';

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        return $this->getDefaultNamespace(trim($rootNamespace, '\\')).'\\'.$name;
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Services';
    }

    protected function getStub()
    {
        return $this->resolveStubPath('/app/Console/Commands/stubs/service.stub');
    }

    protected function resolveStubPath($stub): string
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }
}
