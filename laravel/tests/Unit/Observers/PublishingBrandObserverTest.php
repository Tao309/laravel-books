<?php

namespace Tests\Unit\Observers;

use App\CacheProviders\PublishingBrandsCacheProvider;
use App\Observers\PublishingBrandObserver;
use PHPUnit\Framework\TestCase;

class PublishingBrandObserverTest extends TestCase
{
    public function test_created_invalidates_cache(): void
    {
        $this->assertEventInvalidatesCache(fn (PublishingBrandObserver $observer) => $observer->created());
    }

    public function test_updated_invalidates_cache(): void
    {
        $this->assertEventInvalidatesCache(fn (PublishingBrandObserver $observer) => $observer->updated());
    }

    public function test_deleted_invalidates_cache(): void
    {
        $this->assertEventInvalidatesCache(fn (PublishingBrandObserver $observer) => $observer->deleted());
    }

    public function test_restored_invalidates_cache(): void
    {
        $this->assertEventInvalidatesCache(fn (PublishingBrandObserver $observer) => $observer->restored());
    }

    public function test_force_deleted_invalidates_cache(): void
    {
        $this->assertEventInvalidatesCache(fn (PublishingBrandObserver $observer) => $observer->forceDeleted());
    }

    private function assertEventInvalidatesCache(callable $event): void
    {
        $cacheProvider = $this->createMock(PublishingBrandsCacheProvider::class);
        $cacheProvider->expects($this->once())->method('delete');

        $event(new PublishingBrandObserver($cacheProvider));
    }
}
