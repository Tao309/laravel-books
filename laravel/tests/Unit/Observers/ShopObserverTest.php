<?php

namespace Tests\Unit\Observers;

use App\Observers\ShopObserver;
use PHPUnit\Framework\TestCase;

class ShopObserverTest extends TestCase
{
    public function test_class_exists_and_has_events(): void
    {
        $this->assertTrue(class_exists(ShopObserver::class));
        foreach (['created', 'updated', 'deleted', 'restored', 'forceDeleted'] as $method) {
            $this->assertTrue(method_exists(ShopObserver::class, $method));
        }
    }
}
