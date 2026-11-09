<?php

namespace App\Observers;

use App\CacheProviders\PublishingBrandsCacheProvider;

readonly class PublishingBrandObserver
{
    public function __construct(
        private PublishingBrandsCacheProvider $cacheProvider,
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
