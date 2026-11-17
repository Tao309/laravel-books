<?php

namespace Tests\Unit\Observers;

use App\CacheProviders\BookSeriesCacheProvider;
use App\Observers\BookSeriesObserver;
use PHPUnit\Framework\TestCase;

class BookSeriesObserverTest extends TestCase
{
    public function test_created_invalidates_cache(): void
    {
        $this->assertEventInvalidatesCache(fn (BookSeriesObserver $observer) => $observer->created());
    }

    public function test_updated_invalidates_cache(): void
    {
        $this->assertEventInvalidatesCache(fn (BookSeriesObserver $observer) => $observer->updated());
    }

    public function test_deleted_invalidates_cache(): void
    {
        $this->assertEventInvalidatesCache(fn (BookSeriesObserver $observer) => $observer->deleted());
    }

    public function test_restored_invalidates_cache(): void
    {
        $this->assertEventInvalidatesCache(fn (BookSeriesObserver $observer) => $observer->restored());
    }

    public function test_force_deleted_invalidates_cache(): void
    {
        $this->assertEventInvalidatesCache(fn (BookSeriesObserver $observer) => $observer->forceDeleted());
    }

    private function assertEventInvalidatesCache(callable $event): void
    {
        $cacheProvider = $this->createMock(BookSeriesCacheProvider::class);
        $cacheProvider->expects($this->once())->method('delete');

        $event(new BookSeriesObserver($cacheProvider));
    }
}
