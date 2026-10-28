<?php

namespace App\Providers;

use App\CQRS\Commands\CommandBus;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CommandBus::class);
    }

    public function boot(): void {}
}
