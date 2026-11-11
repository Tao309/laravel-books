<?php

namespace App\Providers;

use App\CQRS\Handlers\Authors\CreateAuthorHandler;
use App\CQRS\Handlers\Authors\DestroyAuthorHandler;
use App\CQRS\Handlers\Authors\GetAuthorByIdHandler;
use App\CQRS\Handlers\Authors\GetAuthorsHandler;
use App\CQRS\Handlers\Authors\UpdateAuthorHandler;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Resources\Authors\AuthorResource;
use App\Repositories\AuthorRepository;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Services\AuthorService;
use App\Services\ServiceInterface;
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

        $this->app->when(AuthorController::class)
            ->needs(ServiceInterface::class)
            ->give(AuthorService::class)
            ->needs('$resourceClass')
            ->give(AuthorResource::class);
    }

    public function boot(): void {}
}
