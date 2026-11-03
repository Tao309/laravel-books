<?php

namespace App\Providers;

use App\CQRS\Handlers\PaperTypes\CreatePaperTypeHandler;
use App\CQRS\Handlers\PaperTypes\DestroyPaperTypeHandler;
use App\CQRS\Handlers\PaperTypes\GetByIdPaperTypeHandler;
use App\CQRS\Handlers\PaperTypes\GetPaperTypesHandler;
use App\CQRS\Handlers\PaperTypes\UpdatePaperTypeHandler;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\PaperTypeRepository;
use Illuminate\Support\ServiceProvider;

class PaperTypeProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->when(CreatePaperTypeHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(PaperTypeRepository::class);

        $this->app->when(GetPaperTypesHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(PaperTypeRepository::class);

        $this->app->when(GetByIdPaperTypeHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(PaperTypeRepository::class);

        $this->app->when(UpdatePaperTypeHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(PaperTypeRepository::class);

        $this->app->when(DestroyPaperTypeHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(PaperTypeRepository::class);
    }

    public function boot(): void {}
}
