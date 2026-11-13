<?php

namespace App\Providers;

use App\CQRS\Handlers\BindingTypes\CreateBindingTypeHandler;
use App\CQRS\Handlers\BindingTypes\DestroyBindingTypeHandler;
use App\CQRS\Handlers\BindingTypes\GetBindingTypesHandler;
use App\CQRS\Handlers\BindingTypes\UpdateBindingTypeHandler;
use App\Http\Controllers\Api\BindingTypeController;
use App\Repositories\BindingTypeRepository;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Services\BindingTypeService;
use App\Services\Interfaces\ServiceInterface;
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

        $this->app->when(BindingTypeController::class)
            ->needs(ServiceInterface::class)
            ->give(BindingTypeService::class);
    }

    public function boot(): void {}
}
