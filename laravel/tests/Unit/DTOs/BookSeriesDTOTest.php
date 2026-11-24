<?php

namespace Tests\Unit\DTOs;

use App\DTOs\BookSeriesDTO;
use App\Models\BookSeries;
use PHPUnit\Framework\TestCase;

class BookSeriesDTOTest extends TestCase
{
    public function test_from_array_maps_entity_data(): void
    {
        $dto = BookSeriesDTO::fromArray([BookSeries::PARAM_NAME => '?????']);
        $this->assertSame([BookSeries::PARAM_NAME => '?????'], $dto->getEntityData());
        $this->assertSame([], $dto->getRelationsData());
    }
}
