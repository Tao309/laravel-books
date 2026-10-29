<?php

namespace App\Console\Commands\CQRSCommands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

abstract class AbstractCQRSActionCommand extends GeneratorCommand
{
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the '.strtolower($this->type)],
            ['action', InputArgument::REQUIRED, 'The action of the '.strtolower($this->type)],
        ];
    }

    protected function handleActionCommand(string $type): bool
    {
        $name = $this->argument('name');
        $action = $this->argument('action');

        if ($action === 'get') {
            $name = Str::plural($name);
        }

        $currentClassName = $this->customQualifyClass($type, $action, $name);

        $classNamePath = $this->getPath($currentClassName);

        $this->makeDirectory($classNamePath);

        $this->files->put($classNamePath, $this->sortImports(
            $this->buildCustomClass($type, $action, $currentClassName)
        ));

        $this->components->info(sprintf('%s [%s] created successfully.', $this->type, $classNamePath));

        return true;
    }

    protected function replaceNamespace(&$stub, $name)
    {
        parent::replaceNamespace($stub, $name);

        $action = $this->argument('action');

        $searchAction = Str::ucfirst($this->argument('action'));

        $singularName = Str::singular($this->getNameInput());
        $pluralName = Str::plural($this->getNameInput());

        $commandName = $action === 'get' ? $pluralName : $singularName;
        $commandName = Str::ucfirst($commandName);

        $searches = [
            [
                '{{ ModelNamespace }}',
                '{{ ModelName }}',
                '{{ RepositoryNamespace }}',
                '{{ RepositoryName }}',
                '{{ '.$searchAction.'CommandNamespace }}',
                '{{ '.$searchAction.'CommandName }}',
                '{{ AbstractHandlerName }}',
            ],
        ];

        foreach ($searches as $search) {
            $stub = str_replace(
                $search,
                [
                    'App\\Models\\'.$singularName,
                    $singularName,
                    'App\\Repositories\\'.$singularName.'Repository',
                    $singularName.'Repository',
                    'App\\CQRS\\Commands\\'.$pluralName.'\\'.$searchAction.$commandName.'Command',
                    $searchAction.$commandName.'Command',
                    'Abstract'.$singularName.'Handler',
                ],
                $stub
            );
        }

        return $this;
    }

    protected function customQualifyClass($type, $action, $className)
    {
        $name = ltrim($className, '\\/');
        $name = str_replace('/', '\\', $name);

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        return $this->qualifyClass(
            $this->getDefaultTypeNamespace(trim($rootNamespace, '\\'), $type).
            '\\'.Str::plural($className).
            '\\'.Str::ucfirst($action).Str::ucfirst($className).Str::ucfirst($type)
        );
    }

    protected function getDefaultTypeNamespace(string $rootNamespace, string $type): string
    {
        return $rootNamespace.'\\CQRS\\'.Str::ucfirst(Str::plural($type));
    }

    protected function buildCustomClass(string $type, string $action, string $name)
    {
        $stub = $this->files->get($this->getActionStub($type, $action));

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    protected function getActionStub(string $type, string $action): string
    {
        return $this->resolveStubPath('/app/Console/Commands/stubs/cqrs-'.$type.'-'.$action.'.stub');
    }

    protected function getStub()
    {
        return null;
    }

    protected function resolveStubPath($stub): string
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }
}
