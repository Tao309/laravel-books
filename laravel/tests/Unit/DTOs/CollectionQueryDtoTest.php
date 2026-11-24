<?php

namespace Tests\Unit\DTOs;

use App\DTOs\CollectionQueryDto;
use PHPUnit\Framework\TestCase;

class CollectionQueryDtoTest extends TestCase
{
    public function test_from_array_and_filters(): void
    {
        $dto = CollectionQueryDto::fromArray([
            'search' => 'query',
            'limit' => 10,
            'id' => [1, 2],
        ]);

        $this->assertSame('query', $dto->getSearch());
        $this->assertSame(10, $dto->getLimit());
        $this->assertSame([1, 2], $dto->getIds());
        $this->assertTrue($dto->hasFilters());
    }

    public function test_defaults(): void
    {
        $dto = new CollectionQueryDto;

        $this->assertNull($dto->getSearch());
        $this->assertSame(30, $dto->getLimit());
        $this->assertSame([], $dto->getIds());
    }
}
