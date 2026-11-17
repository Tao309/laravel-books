<?php

namespace Tests\Unit\Observers;

use App\CacheProviders\BindingTypesCacheProvider;
use App\Observers\BindingTypeObserver;
use PHPUnit\Framework\TestCase;

class BindingTypeObserverTest extends TestCase
{
    public function test_created_invalidates_cache(): void
    {
        $this->assertEventInvalidatesCache(fn (BindingTypeObserver $observer) => $observer->created());
    }

    public function test_updated_invalidates_cache(): void
    {
        $this->assertEventInvalidatesCache(fn (BindingTypeObserver $observer) => $observer->updated());
    }

    public function test_deleted_invalidates_cache(): void
    {
        $this->assertEventInvalidatesCache(fn (BindingTypeObserver $observer) => $observer->deleted());
    }

    public function test_restored_invalidates_cache(): void
    {
        $this->assertEventInvalidatesCache(fn (BindingTypeObserver $observer) => $observer->restored());
    }

    public function test_force_deleted_invalidates_cache(): void
    {
        $this->assertEventInvalidatesCache(fn (BindingTypeObserver $observer) => $observer->forceDeleted());
    }

    private function assertEventInvalidatesCache(callable $event): void
    {
        $cacheProvider = $this->createMock(BindingTypesCacheProvider::class);
        $cacheProvider->expects($this->once())->method('delete');

        $event(new BindingTypeObserver($cacheProvider));
    }
}
