<?php

namespace App\Providers;

use App\CQRS\Handlers\SourceProductTypes\CreateSourceProductTypeHandler;
use App\CQRS\Handlers\SourceProductTypes\DestroySourceProductTypeHandler;
use App\CQRS\Handlers\SourceProductTypes\GetSourceProductTypesHandler;
use App\CQRS\Handlers\SourceProductTypes\UpdateSourceProductTypeHandler;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\SourceProductTypeRepository;
use Illuminate\Support\ServiceProvider;

class SourceProductTypeProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->when(GetSourceProductTypesHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(SourceProductTypeRepository::class);

        $this->app->when(CreateSourceProductTypeHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(SourceProductTypeRepository::class);

        $this->app->when(UpdateSourceProductTypeHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(SourceProductTypeRepository::class);

        $this->app->when(DestroySourceProductTypeHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(SourceProductTypeRepository::class);
    }

    public function boot(): void {}
}
