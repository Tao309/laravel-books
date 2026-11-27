<?php

namespace App\Providers;

use App\CQRS\Handlers\Brands\CreateBrandHandler;
use App\CQRS\Handlers\Brands\DestroyBrandHandler;
use App\CQRS\Handlers\Brands\GetBrandByIdHandler;
use App\CQRS\Handlers\Brands\GetBrandsHandler;
use App\CQRS\Handlers\Brands\GetBrandsPaginateHandler;
use App\CQRS\Handlers\Brands\UpdateBrandHandler;
use App\Repositories\BrandRepository;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Support\ServiceProvider;

class BrandProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->when(CreateBrandHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(BrandRepository::class);

        $this->app->when(GetBrandsHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(BrandRepository::class);

        $this->app->when(GetBrandsPaginateHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(BrandRepository::class);

        $this->app->when(GetBrandByIdHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(BrandRepository::class);

        $this->app->when(UpdateBrandHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(BrandRepository::class);

        $this->app->when(DestroyBrandHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(BrandRepository::class);
    }

    public function boot(): void {}
}
