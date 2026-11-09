<?php

namespace App\Observers;

use App\CacheProviders\SourceProductsTypesCacheProvider;

readonly class SourceProductTypeObserver
{
    public function __construct(
        private SourceProductsTypesCacheProvider $cacheProvider,
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
