<?php

namespace Tests\Unit\Observers;

use App\CacheProviders\SourceProductsTypesCacheProvider;
use App\Observers\SourceProductTypeObserver;
use PHPUnit\Framework\TestCase;

class SourceProductTypeObserverTest extends TestCase
{
    public function test_created_invalidates_cache(): void
    {
        $this->assertEventInvalidatesCache(fn (SourceProductTypeObserver $observer) => $observer->created());
    }

    public function test_updated_invalidates_cache(): void
    {
        $this->assertEventInvalidatesCache(fn (SourceProductTypeObserver $observer) => $observer->updated());
    }

    public function test_deleted_invalidates_cache(): void
    {
        $this->assertEventInvalidatesCache(fn (SourceProductTypeObserver $observer) => $observer->deleted());
    }

    public function test_restored_invalidates_cache(): void
    {
        $this->assertEventInvalidatesCache(fn (SourceProductTypeObserver $observer) => $observer->restored());
    }

    public function test_force_deleted_invalidates_cache(): void
    {
        $this->assertEventInvalidatesCache(fn (SourceProductTypeObserver $observer) => $observer->forceDeleted());
    }

    private function assertEventInvalidatesCache(callable $event): void
    {
        $cacheProvider = $this->createMock(SourceProductsTypesCacheProvider::class);
        $cacheProvider->expects($this->once())->method('delete');

        $event(new SourceProductTypeObserver($cacheProvider));
    }
}
