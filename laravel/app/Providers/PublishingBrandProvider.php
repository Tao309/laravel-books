<?php

namespace App\Providers;

use App\CQRS\Handlers\PublishingBrands\CreatePublishingBrandHandler;
use App\CQRS\Handlers\PublishingBrands\GetPublishingBrandByIdHandler;
use App\CQRS\Handlers\PublishingBrands\GetPublishingBrandsHandler;
use App\CQRS\Handlers\PublishingBrands\UpdatePublishingBrandHandler;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\PublishingBrandRepository;
use Illuminate\Support\ServiceProvider;

class PublishingBrandProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->when(CreatePublishingBrandHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(PublishingBrandRepository::class);

        $this->app->when(GetPublishingBrandsHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(PublishingBrandRepository::class);

        $this->app->when(GetPublishingBrandByIdHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(PublishingBrandRepository::class);

        $this->app->when(UpdatePublishingBrandHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(PublishingBrandRepository::class);
    }

    public function boot(): void {}
}
