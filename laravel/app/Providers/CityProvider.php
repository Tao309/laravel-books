<?php

namespace App\Providers;

use App\Handlers\Cities\CreateCityHandler;
use App\Handlers\Cities\DestroyCityHandler;
use App\Handlers\Cities\GetCitiesHandler;
use App\Handlers\Cities\UpdateCityHandler;
use App\Repositories\CityRepository;
use App\Repositories\Interfaces\RepositoryInterface;
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
    }

    public function boot(): void {}
}
