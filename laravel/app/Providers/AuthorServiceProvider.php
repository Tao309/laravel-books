<?php

namespace App\Providers;

use App\Handlers\Authors\CreateAuthorHandler;
use App\Handlers\Authors\DestroyAuthorHandler;
use App\Handlers\Authors\GetAuthorsHandler;
use App\Handlers\Authors\GetByIdAuthorHandler;
use App\Handlers\Authors\UpdateAuthorHandler;
use App\Repositories\AuthorRepository;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AuthorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->when(CreateAuthorHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(AuthorRepository::class);

        $this->app->when(GetAuthorsHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(AuthorRepository::class);

        $this->app->when(GetByIdAuthorHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(AuthorRepository::class);

        $this->app->when(UpdateAuthorHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(AuthorRepository::class);

        $this->app->when(DestroyAuthorHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(AuthorRepository::class);
    }

    public function boot(): void {}
}
