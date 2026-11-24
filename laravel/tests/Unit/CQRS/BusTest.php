<?php

namespace Tests\Unit\CQRS;

use App\CQRS\Bus;
use App\Exceptions\HandlerNotFoundException;
use Illuminate\Contracts\Container\Container;
use PHPUnit\Framework\TestCase;
use stdClass;

class BusTest extends TestCase
{
    public function test_throws_for_invalid_message_namespace(): void
    {
        $bus = new Bus($this->createMock(Container::class));
        $this->expectException(HandlerNotFoundException::class);
        $bus->handle(new stdClass);
    }
}
