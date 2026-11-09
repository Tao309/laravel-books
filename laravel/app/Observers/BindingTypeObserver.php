<?php

namespace App\Observers;

use App\CacheProviders\BindingTypesCacheProvider;

readonly class BindingTypeObserver
{
    public function __construct(
        private BindingTypesCacheProvider $cacheProvider,
    ) {}

    public function created(): void
    {
        $this->cacheProvider->delete();
    }

    public function updated(): void
    {
        $this->cacheProvider->delete();
    }

    public function deleted(): void
    {
        $this->cacheProvider->delete();
    }

    public function restored(): void
    {
        $this->cacheProvider->delete();
    }

    public function forceDeleted(): void
    {
        $this->cacheProvider->delete();
    }
}
