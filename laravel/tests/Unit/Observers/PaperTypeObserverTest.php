<?php

namespace Tests\Unit\Observers;

use App\Observers\PaperTypeObserver;
use PHPUnit\Framework\TestCase;

class PaperTypeObserverTest extends TestCase
{
    public function test_class_exists_and_has_events(): void
    {
        $this->assertTrue(class_exists(PaperTypeObserver::class));
        foreach (['created', 'updated', 'deleted', 'restored', 'forceDeleted'] as $method) {
            $this->assertTrue(method_exists(PaperTypeObserver::class, $method));
        }
    }
}
