<?php

namespace App\Providers;

use App\CQRS\Handlers\SourceProducts\CreateSourceProductHandler;
use App\CQRS\Handlers\SourceProducts\DestroySourceProductHandler;
use App\CQRS\Handlers\SourceProducts\GetSourceProductByIdSHandler;
use App\CQRS\Handlers\SourceProducts\GetSourceProductsHandler;
use App\CQRS\Handlers\SourceProducts\UpdateSourceProductHandler;
use App\Http\Controllers\Api\SourceProductController;
use App\Http\Resources\SourceProducts\SourceProductResource;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\SourceProductRepository;
use App\Services\Interfaces\ServiceInterface;
use App\Services\SourceProductService;
use Illuminate\Support\ServiceProvider;

class SourceProductProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->when(GetSourceProductByIdSHandler::class)
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

        $this->app->when(SourceProductController::class)
            ->needs(ServiceInterface::class)
            ->give(SourceProductService::class)
            ->needs('$resourceClass')
            ->give(SourceProductResource::class);
    }

    public function boot(): void {}
}
