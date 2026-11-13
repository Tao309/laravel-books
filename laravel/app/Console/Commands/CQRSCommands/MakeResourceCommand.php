<?php

namespace App\Console\Commands\CQRSCommands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'app:make:resource')]
class MakeResourceCommand extends GeneratorCommand
{
    protected $name = 'app:make:resource';

    protected $description = 'Create a new resource';

    protected $type = 'Resource';

    public function handle()
    {
        if ($this->collection()) {
            $this->type = 'Resource collection';
        }

        parent::handle();
    }

    protected function qualifyClass($name)
    {
        $name = ltrim($name, '\\/');
        $name = str_replace('/', '\\', $name);

        if ($this->collection()) {
            $name = Str::plural($name).'ResourceCollection';
        } else {
            $name = $name.'Resource';
        }

        $name = $this->collection() ? Str::plural($name).'ResourceCollection' : $name.'Resource';

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        return $this->getDefaultNamespace(trim($rootNamespace, '\\')).'\\'.$name;
    }

    protected function getStub()
    {
        return match (true) {
            $this->collection() && $this->isData() => $this->resolveStubPath('/app/Console/Commands/stubs/resource-collection-data.stub'),
            $this->collection() => $this->resolveStubPath('/app/Console/Commands/stubs/resource-collection.stub'),
            $this->isData() => $this->resolveStubPath('/app/Console/Commands/stubs/resource-data.stub'),
            default => $this->resolveStubPath('/app/Console/Commands/stubs/resource.stub'),
        };
    }

    protected function collection()
    {
        return $this->option('collection') ||
            str_ends_with($this->argument('name'), 'Collection');
    }

    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Resources\\'.Str::plural(Str::ucfirst($this->getNameInput()));
    }

    protected function getOptions()
    {
        return [
            ['collection', 'c', InputOption::VALUE_NONE, 'Create a resource collection'],
        ];
    }

    protected function replaceNamespace(&$stub, $name)
    {
        parent::replaceNamespace($stub, $name);

        $searches = [
            [
                '{{ ModelNameSingular }}',
                '{{ ModelNamePlural }}',
            ],
        ];

        $modelName = Str::ucfirst($this->getNameInput());

        foreach ($searches as $search) {
            $stub = str_replace(
                $search,
                [
                    $modelName,
                    Str::plural($modelName),
                ],
                $stub
            );
        }

        return $this;
    }
}
