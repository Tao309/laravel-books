<?php

namespace Tests\Unit\Observers;

use App\Observers\PublishingHouseObserver;
use PHPUnit\Framework\TestCase;

class PublishingHouseObserverTest extends TestCase
{
    public function test_class_exists_and_has_events(): void
    {
        $this->assertTrue(class_exists(PublishingHouseObserver::class));
        foreach (['created', 'updated', 'deleted', 'restored', 'forceDeleted'] as $method) {
            $this->assertTrue(method_exists(PublishingHouseObserver::class, $method));
        }
    }
}
