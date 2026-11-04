<?php

namespace App\Providers;

use App\CQRS\Handlers\SourceProducts\CreateSourceProductHandler;
use App\CQRS\Handlers\SourceProducts\DestroySourceProductHandler;
use App\CQRS\Handlers\SourceProducts\GetByIdSourceProductHandler;
use App\CQRS\Handlers\SourceProducts\GetSourceProductsHandler;
use App\CQRS\Handlers\SourceProducts\UpdateSourceProductHandler;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\SourceProductRepository;
use Illuminate\Support\ServiceProvider;

class SourceProductProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->when(GetByIdSourceProductHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(SourceProductRepository::class);

        $this->app->when(GetSourceProductsHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(SourceProductRepository::class);

        $this->app->when(CreateSourceProductHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(SourceProductRepository::class);

        $this->app->when(UpdateSourceProductHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(SourceProductRepository::class);

        $this->app->when(DestroySourceProductHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(SourceProductRepository::class);
    }

    public function boot(): void {}
}
