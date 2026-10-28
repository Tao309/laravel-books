<?php

namespace App\Providers;

use App\CQRS\Handlers\Authors\CreateAuthorHandler;
use App\CQRS\Handlers\Authors\DestroyAuthorHandler;
use App\CQRS\Handlers\Authors\GetAuthorByIdHandler;
use App\CQRS\Handlers\Authors\GetAuthorsHandler;
use App\CQRS\Handlers\Authors\UpdateAuthorHandler;
use App\Repositories\AuthorRepository;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AuthorProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->when(CreateAuthorHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(AuthorRepository::class);

        $this->app->when(GetAuthorsHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(AuthorRepository::class);

        $this->app->when(GetAuthorByIdHandler::class)
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
