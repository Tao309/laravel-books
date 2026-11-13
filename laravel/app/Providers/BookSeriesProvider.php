<?php

namespace App\Providers;

use App\CQRS\Handlers\BookSeries\CreateBookSeriesHandler;
use App\CQRS\Handlers\BookSeries\GetBookSeriesByIdHandler;
use App\CQRS\Handlers\BookSeries\GetBookSeriesHandler;
use App\CQRS\Handlers\BookSeries\UpdateBookSeriesHandler;
use App\Http\Controllers\Api\BookSeriesController;
use App\Repositories\BookSeriesRepository;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Services\BookSeriesService;
use App\Services\Interfaces\ServiceInterface;
use Illuminate\Support\ServiceProvider;

class BookSeriesProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->when(CreateBookSeriesHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(BookSeriesRepository::class);

        $this->app->when(GetBookSeriesHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(BookSeriesRepository::class);

        $this->app->when(GetBookSeriesByIdHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(BookSeriesRepository::class);

        $this->app->when(UpdateBookSeriesHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(BookSeriesRepository::class);

        $this->app->when(BookSeriesController::class)
            ->needs(ServiceInterface::class)
            ->give(BookSeriesService::class);
    }

    public function boot(): void {}
}
