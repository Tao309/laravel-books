<?php

namespace App\Console\Commands\CQRSCommands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(name: 'app:make:model')]
class MakeModelCommand extends GeneratorCommand
{
    protected $name = 'app:make:model';

    protected $description = 'Create a new model';

    protected $type = 'Model';

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

        return parent::handle();
    }

    protected function qualifyClass($name)
    {
        $name = ltrim($name, '\\/');
        $name = str_replace('/', '\\', $name);

        $name = Str::ucfirst($name);

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        return $this->getDefaultNamespace(trim($rootNamespace, '\\')).'\\'.$name;
    }

    protected function getStub()
    {
        return $this->resolveStubPath('/app/Console/Commands/stubs/model-api.stub');
    }

    protected function resolveStubPath($stub): string
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Models';
    }

    protected function replaceNamespace(&$stub, $name)
    {
        parent::replaceNamespace($stub, $name);

        $searches = [
            [
                '{{ ModelNameSingular }}',
                '{{ ModelNamePlural }}',
                '{{ ModelNamePluralLower }}',
                '{{ ModelNameSnakePlural }}',
            ],
        ];

        foreach ($searches as $search) {
            $stub = str_replace(
                $search,
                [
                    Str::ucfirst($this->getNameInput()),
                    Str::plural(Str::ucfirst($this->getNameInput())),
                    Str::plural(Str::lower($this->getNameInput())),
                    Str::snake(Str::plural($this->getNameInput())),
                ],
                $stub
            );
        }

        return $this;
    }
}
