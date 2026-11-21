<?php

namespace App\Providers;

use App\Auth\CustomUserProvider;
use App\CacheProviders\CacheProviderInterface;
use App\CQRS\Bus;
use App\Http\Response\ResponseContext;
use App\Services\SessionShopService;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Bus::class);

        $this->app->singleton(CacheProviderInterface::class);

        $this->app->singleton(SessionShopService::class);

        $this->app->scoped(ResponseContext::class);
    }

    public function boot(): void
    {
        Auth::provider('custom_auth_provider', fn ($app, $config) => $this->createCustomUserProvider($config));

        if (! app()->environment('local')) {
            return;
        }

        DB::listen(function (QueryExecuted $query) {
            Log::channel('sql')->debug('SQL Query', [
                'sql' => $query->sql,
                'bindings' => $query->bindings,
                'time_ms' => $query->time,
                'raw_sql' => $query->toRawSql(),
            ]);
        });
    }

    private function createCustomUserProvider($config): ?CustomUserProvider
    {
        return new CustomUserProvider(
            $this->app['db']->connection($config['connection'] ?? null),
            $config['table'],
        );
    }
}
