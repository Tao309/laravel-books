<?php

namespace Tests\Unit\Observers;

use App\CacheProviders\PublishingHousesCacheProvider;
use App\Observers\PublishingHouseObserver;
use PHPUnit\Framework\TestCase;

class PublishingHouseObserverTest extends TestCase
{
    public function test_created_invalidates_cache(): void
    {
        $this->assertEventInvalidatesCache(fn (PublishingHouseObserver $observer) => $observer->created());
    }

    public function test_updated_invalidates_cache(): void
    {
        $this->assertEventInvalidatesCache(fn (PublishingHouseObserver $observer) => $observer->updated());
    }

    public function test_deleted_invalidates_cache(): void
    {
        $this->assertEventInvalidatesCache(fn (PublishingHouseObserver $observer) => $observer->deleted());
    }

    public function test_restored_invalidates_cache(): void
    {
        $this->assertEventInvalidatesCache(fn (PublishingHouseObserver $observer) => $observer->restored());
    }

    public function test_force_deleted_invalidates_cache(): void
    {
        $this->assertEventInvalidatesCache(fn (PublishingHouseObserver $observer) => $observer->forceDeleted());
    }

    private function assertEventInvalidatesCache(callable $event): void
    {
        $cacheProvider = $this->createMock(PublishingHousesCacheProvider::class);
        $cacheProvider->expects($this->once())->method('delete');

        $event(new PublishingHouseObserver($cacheProvider));
    }
}
