<?php

namespace App\Console\Commands\CQRSCommands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'make:repository', description: 'Create a Repository class.')]
class MakeRepositoryCommand extends GeneratorCommand
{
    protected $signature = 'make:repository {name} {--f|force}';

    protected $description = 'Create a new Repository class';

    protected $type = 'Repository';

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the '.strtolower($this->type)],
        ];
    }

    protected function getOptions()
    {
        return [
            [
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Create the class even if the repository already exists or class model is not exists',
            ],
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

        $searches = [
            ['{{ ModelNamespace }}', '{{ ModelName }}'],
            ['{{ModelNamespace}}', '{{ModelName}}'],
        ];

        foreach ($searches as $search) {
            $stub = str_replace(
                $search,
                ['App\\Models\\'.$this->getNameInput(), $this->getNameInput()],
                $stub
            );
        }

        return $this;
    }

    protected function qualifyClass($name)
    {
        $name = ltrim($name, '\\/');

        $name = str_replace('/', '\\', $name).'Repository';

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        return $this->getDefaultNamespace(trim($rootNamespace, '\\')).'\\'.$name;
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Repositories';
    }

    protected function getStub()
    {
        return $this->resolveStubPath('/app/Console/Commands/stubs/repository.stub');
    }

    protected function resolveStubPath($stub): string
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }
}
