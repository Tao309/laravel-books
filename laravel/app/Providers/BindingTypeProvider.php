<?php

namespace App\Providers;

use App\CQRS\Handlers\BindingTypes\CreateBindingTypeHandler;
use App\CQRS\Handlers\BindingTypes\DestroyBindingTypeHandler;
use App\CQRS\Handlers\BindingTypes\GetBindingTypesHandler;
use App\CQRS\Handlers\BindingTypes\UpdateBindingTypeHandler;
use App\Repositories\BindingTypeRepository;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Support\ServiceProvider;

class BindingTypeProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->when(GetBindingTypesHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(BindingTypeRepository::class);

        $this->app->when(CreateBindingTypeHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(BindingTypeRepository::class);

        $this->app->when(UpdateBindingTypeHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(BindingTypeRepository::class);

        $this->app->when(DestroyBindingTypeHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(BindingTypeRepository::class);
    }

    public function boot(): void {}
}
