<?php

namespace Tests\Unit\Observers;

use App\Observers\BindingTypeObserver;
use PHPUnit\Framework\TestCase;

class BindingTypeObserverTest extends TestCase
{
    public function test_class_exists_and_has_events(): void
    {
        $this->assertTrue(class_exists(BindingTypeObserver::class));
        foreach (['created', 'updated', 'deleted', 'restored', 'forceDeleted'] as $method) {
            $this->assertTrue(method_exists(BindingTypeObserver::class, $method));
        }
    }
}
