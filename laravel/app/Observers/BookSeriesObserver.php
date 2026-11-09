<?php

namespace App\Observers;

use App\CacheProviders\BookSeriesCacheProvider;

readonly class BookSeriesObserver
{
    public function __construct(
        private BookSeriesCacheProvider $cacheProvider,
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
