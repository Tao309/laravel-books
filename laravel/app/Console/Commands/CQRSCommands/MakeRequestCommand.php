<?php

namespace App\Console\Commands\CQRSCommands;

use http\Exception\RuntimeException;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'app:make:request')]
class MakeRequestCommand extends GeneratorCommand
{
    protected $name = 'app:make:request';

    protected $description = 'Create a new request';

    protected $type = 'Request';

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

        if ($this->isAbstract()) {
            $this->type = 'Abstract Request';
        }

        if ($this->isStore()) {
            $this->type = 'Store Request';
        }

        if ($this->isUpdate()) {
            $this->type = 'Update Request';
        }

        return parent::handle();
    }

    protected function qualifyClass($name)
    {
        $name = ltrim($name, '\\/');
        $name = str_replace('/', '\\', $name);

        $name = Str::ucfirst($name);

        if ($this->isAbstract()) {
            $name = 'Abstract'.$name.'Request';
        } elseif ($this->isStore()) {
            $name = 'Store'.$name.'Request';
        } elseif ($this->isUpdate()) {
            $name = 'Update'.$name.'Request';
        }

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        return $this->getDefaultNamespace(trim($rootNamespace, '\\')).'\\'.$name;
    }

    protected function getStub()
    {
        return match (true) {
            $this->isAbstract() => $this->resolveStubPath('/app/Console/Commands/stubs/request-abstract.stub'),
            $this->isStore() => $this->resolveStubPath('/app/Console/Commands/stubs/request-store.stub'),
            $this->isUpdate() => $this->resolveStubPath('/app/Console/Commands/stubs/request-update.stub'),
            default => throw new RuntimeException('Unable to generate stub for '.strtolower($this->type)),
        };
    }

    protected function isStore(): bool
    {
        return $this->option('store') || str_ends_with($this->argument('name'), 'store');
    }

    protected function isUpdate(): bool
    {
        return $this->option('update') || str_ends_with($this->argument('name'), 'update');
    }

    protected function isAbstract(): bool
    {
        return $this->option('abstract') || str_ends_with($this->argument('name'), 'abstract');
    }

    protected function getOptions()
    {
        return [
            ['abstract', 'a', InputOption::VALUE_NONE, 'Create as Abstract Request'],
            ['store', 's', InputOption::VALUE_NONE, 'Create as Store Request'],
            ['update', 'u', InputOption::VALUE_NONE, 'Create as Update Request'],
        ];
    }

    protected function resolveStubPath($stub): string
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\\Http\\Requests\\'.Str::plural(Str::ucfirst($this->getNameInput()));
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
                '{{ ModelNameSnakeSingular }}',
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
                    Str::snake(Str::singular($this->getNameInput())),
                ],
                $stub
            );
        }

        return $this;
    }
}
