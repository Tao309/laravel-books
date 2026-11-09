<?php

namespace App\Providers;

use App\CQRS\Handlers\Books\CreateBookHandler;
use App\CQRS\Handlers\Books\DestroyBookHandler;
use App\CQRS\Handlers\Books\GetBooksHandler;
use App\CQRS\Handlers\Books\GetBooksPaginateHandler;
use App\CQRS\Handlers\Books\GetByIdBookHandler;
use App\CQRS\Handlers\Books\UpdateBookHandler;
use App\Repositories\BookRepository;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Support\ServiceProvider;

class BookProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->when(CreateBookHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(BookRepository::class);

        $this->app->when(GetBooksHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(BookRepository::class);

        $this->app->when(GetBooksPaginateHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(BookRepository::class);

        $this->app->when(GetByIdBookHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(BookRepository::class);

        $this->app->when(UpdateBookHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(BookRepository::class);

        $this->app->when(DestroyBookHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(BookRepository::class);
    }

    public function boot(): void {}
}
