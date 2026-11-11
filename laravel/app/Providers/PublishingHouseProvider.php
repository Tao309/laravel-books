<?php

namespace App\Providers;

use App\CQRS\Handlers\PublishingHouses\CreatePublishingHouseHandler;
use App\CQRS\Handlers\PublishingHouses\GetPublishingHouseByIdHandler;
use App\CQRS\Handlers\PublishingHouses\GetPublishingHousesHandler;
use App\CQRS\Handlers\PublishingHouses\UpdatePublishingHouseHandler;
use App\Http\Controllers\Api\PublishingHouseController;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\PublishingHouseRepository;
use App\Services\PublishingHouseService;
use App\Services\ServiceInterface;
use Illuminate\Support\ServiceProvider;

class PublishingHouseProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->when(CreatePublishingHouseHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(PublishingHouseRepository::class);

        $this->app->when(GetPublishingHousesHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(PublishingHouseRepository::class);

        $this->app->when(GetPublishingHouseByIdHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(PublishingHouseRepository::class);

        $this->app->when(UpdatePublishingHouseHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(PublishingHouseRepository::class);

        $this->app->when(PublishingHouseController::class)
            ->needs(ServiceInterface::class)
            ->give(PublishingHouseService::class);
    }

    public function boot(): void {}
}
