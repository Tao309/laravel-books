<?php

namespace Tests\Unit\Observers;

use App\Observers\PublishingBrandObserver;
use PHPUnit\Framework\TestCase;

class PublishingBrandObserverTest extends TestCase
{
    public function test_class_exists_and_has_events(): void
    {
        $this->assertTrue(class_exists(PublishingBrandObserver::class));
        foreach (['created', 'updated', 'deleted', 'restored', 'forceDeleted'] as $method) {
            $this->assertTrue(method_exists(PublishingBrandObserver::class, $method));
        }
    }
}
