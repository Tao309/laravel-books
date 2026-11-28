<?php

namespace App\Providers;

use App\CQRS\Handlers\Brands\CreateBrandHandler;
use App\CQRS\Handlers\Brands\DestroyBrandHandler;
use App\CQRS\Handlers\Brands\GetBrandByIdHandler;
use App\CQRS\Handlers\Brands\GetBrandsHandler;
use App\CQRS\Handlers\Brands\GetBrandsPaginateHandler;
use App\CQRS\Handlers\Brands\UpdateBrandHandler;
use App\Http\Controllers\Api\BrandController;
use App\Http\Resources\Brands\BrandResource;
use App\Repositories\BrandRepository;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Services\BrandService;
use App\Services\Interfaces\ServiceInterface;
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

        $this->app->when(BrandController::class)
            ->needs(ServiceInterface::class)
            ->give(BrandService::class)
            ->needs('$resourceClass')
            ->give(BrandResource::class);
    }

    public function boot(): void {}
}
