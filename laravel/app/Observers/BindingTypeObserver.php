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
        $this->deleteCache();
    }

    public function updated(): void
    {
        $this->deleteCache();
    }

    public function deleted(): void
    {
        $this->deleteCache();
    }

    public function restored(): void
    {
        $this->deleteCache();
    }

    public function forceDeleted(): void
    {
        $this->deleteCache();
    }

    private function deleteCache(): void
    {
        $this->cacheProvider->delete();
    }
}
