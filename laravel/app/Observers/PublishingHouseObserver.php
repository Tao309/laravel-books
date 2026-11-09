<?php

namespace App\Observers;

use App\CacheProviders\PublishingHousesCacheProvider;

readonly class PublishingHouseObserver
{
    public function __construct(
        private PublishingHousesCacheProvider $cacheProvider,
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
