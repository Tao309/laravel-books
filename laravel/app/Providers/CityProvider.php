<?php

namespace App\Providers;

use App\CQRS\Handlers\Cities\CreateCityHandler;
use App\CQRS\Handlers\Cities\DestroyCityHandler;
use App\CQRS\Handlers\Cities\GetCitiesHandler;
use App\CQRS\Handlers\Cities\UpdateCityHandler;
use App\Http\Controllers\Api\CityController;
use App\Repositories\CityRepository;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Services\CityService;
use App\Services\ServiceInterface;
use Illuminate\Support\ServiceProvider;

class CityProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->when(GetCitiesHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(CityRepository::class);

        $this->app->when(CreateCityHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(CityRepository::class);

        $this->app->when(UpdateCityHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(CityRepository::class);

        $this->app->when(DestroyCityHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(CityRepository::class);

        $this->app->when(CityController::class)
            ->needs(ServiceInterface::class)
            ->give(CityService::class);
    }

    public function boot(): void {}
}
