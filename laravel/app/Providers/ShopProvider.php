<?php

namespace App\Providers;

use App\CQRS\Handlers\Shops\GetShopByIdHandler;
use App\CQRS\Handlers\Shops\GetShopsHandler;
use App\Http\Controllers\Api\ShopController;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\ShopRepository;
use App\Services\Interfaces\ServiceInterface;
use App\Services\ShopService;
use Illuminate\Support\ServiceProvider;

class ShopProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->when(GetShopsHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(ShopRepository::class);

        $this->app->when(GetShopByIdHandler::class)
            ->needs(RepositoryInterface::class)
            ->give(ShopRepository::class);

        $this->app->when(ShopController::class)
            ->needs(ServiceInterface::class)
            ->give(ShopService::class);
    }

    public function boot(): void {}
}
