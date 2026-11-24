<?php

namespace Tests\Unit\Observers;

use App\Observers\SourceProductTypeObserver;
use PHPUnit\Framework\TestCase;

class SourceProductTypeObserverTest extends TestCase
{
    public function test_class_exists_and_has_events(): void
    {
        $this->assertTrue(class_exists(SourceProductTypeObserver::class));
        foreach (['created', 'updated', 'deleted', 'restored', 'forceDeleted'] as $method) {
            $this->assertTrue(method_exists(SourceProductTypeObserver::class, $method));
        }
    }
}
