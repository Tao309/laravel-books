<?php

namespace Tests\Unit\Observers;

use App\Observers\BookSeriesObserver;
use PHPUnit\Framework\TestCase;

class BookSeriesObserverTest extends TestCase
{
    public function test_class_exists_and_has_events(): void
    {
        $this->assertTrue(class_exists(BookSeriesObserver::class));
        foreach (['created', 'updated', 'deleted', 'restored', 'forceDeleted'] as $method) {
            $this->assertTrue(method_exists(BookSeriesObserver::class, $method));
        }
    }
}
