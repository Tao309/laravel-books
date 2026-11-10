<?php

namespace App\Providers;

use App\CQRS\Handlers\Products\CreateProductHandler;
use App\CQRS\Handlers\Products\DestroyProductHandler;
use App\CQRS\Handlers\Products\GetByIdProductHandler;
use App\CQRS\Handlers\Products\GetProductsHandler;
use App\CQRS\Handlers\Products\GetProductsPaginateHandler;
use App\CQRS\Handlers\Products\UpdateProductHandler;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\ProductRepository;
use Illuminate\Support\ServiceProvider;

class ProductProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->when(CreateProductHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(ProductRepository::class);

        $this->app->when(GetProductsHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(ProductRepository::class);

        $this->app->when(GetProductsPaginateHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(ProductRepository::class);

        $this->app->when(GetByIdProductHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(ProductRepository::class);

        $this->app->when(UpdateProductHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(ProductRepository::class);

        $this->app->when(DestroyProductHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(ProductRepository::class);
    }

    public function boot(): void {}
}
