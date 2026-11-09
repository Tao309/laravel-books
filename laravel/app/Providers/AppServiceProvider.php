<?php

namespace App\Providers;

use App\Auth\CustomUserProvider;
use App\CacheProviders\CacheProviderInterface;
use App\CQRS\CommandBus;
use App\Services\SessionShopService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CommandBus::class);

        $this->app->singleton(CacheProviderInterface::class);

        $this->app->singleton(SessionShopService::class);
    }

    public function boot(): void
    {
        Auth::provider('custom_auth_provider', fn ($app, $config) => $this->createCustomUserProvider($config));
    }

    private function createCustomUserProvider($config): ?CustomUserProvider
    {
        return new CustomUserProvider(
            $this->app['db']->connection($config['connection'] ?? null),
            $config['table'],
        );
    }
}
