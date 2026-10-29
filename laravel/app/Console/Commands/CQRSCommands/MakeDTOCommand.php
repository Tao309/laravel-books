<?php

namespace App\Console\Commands\CQRSCommands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(name: 'make:dto', description: 'Create a DTO class.')]
class MakeDTOCommand extends GeneratorCommand
{
    protected $signature = 'make:dto {name}';

    protected $description = 'Create a new DTO class';

    protected $type = 'DTO';

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

    protected function qualifyClass($name)
    {
        $name = ltrim($name, '\\/');

        $name = str_replace('/', '\\', $name).'DTO';

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        return $this->getDefaultNamespace(trim($rootNamespace, '\\')).'\\'.$name;
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\DTOs';
    }

    protected function getStub()
    {
        return $this->resolveStubPath('/app/Console/Commands/stubs/dto.stub');
    }

    protected function resolveStubPath($stub): string
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }
}
