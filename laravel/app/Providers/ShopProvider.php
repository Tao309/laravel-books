<?php

namespace App\Providers;

use App\Handlers\Shops\GetShopByIdHandler;
use App\Handlers\Shops\GetShopsHandler;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\ShopRepository;
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
    }

    public function boot(): void {}
}
