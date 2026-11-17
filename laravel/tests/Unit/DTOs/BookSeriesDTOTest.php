<?php

namespace Tests\Unit\DTOs;

use App\DTOs\BookSeriesDTO;
use App\Models\BookSeries;
use PHPUnit\Framework\TestCase;

class BookSeriesDTOTest extends TestCase
{
    public function test_from_array_maps_name(): void
    {
        $dto = BookSeriesDTO::fromArray([
            BookSeries::PARAM_NAME => 'Мировая классика',
        ]);

        $this->assertSame('Мировая классика', $dto->name);
    }

    public function test_entity_data_contains_name(): void
    {
        $dto = new BookSeriesDTO('Мировая классика');

        $this->assertSame(
            [BookSeries::PARAM_NAME => 'Мировая классика'],
            $dto->getEntityData(),
        );
    }

    public function test_relations_data_is_empty(): void
    {
        $dto = new BookSeriesDTO('Мировая классика');

        $this->assertSame([], $dto->getRelationsData());
    }

    public function test_to_array_merges_entity_and_relations_data(): void
    {
        $dto = new BookSeriesDTO('Мировая классика');

        $this->assertSame(
            [BookSeries::PARAM_NAME => 'Мировая классика'],
            $dto->toArray(),
        );
    }
}
