<?php

namespace Tests\Unit\DTOs;

use App\DTOs\PublishingHouseDTO;
use App\Models\PublishingHouse;
use PHPUnit\Framework\TestCase;

class PublishingHouseDTOTest extends TestCase
{
    public function test_from_array_maps_name(): void
    {
        $dto = PublishingHouseDTO::fromArray([
            PublishingHouse::PARAM_NAME => 'Полярная звезда',
        ]);

        $this->assertSame('Полярная звезда', $dto->name);
    }

    public function test_entity_data_contains_name(): void
    {
        $dto = new PublishingHouseDTO('Полярная звезда');

        $this->assertSame([PublishingHouse::PARAM_NAME => 'Полярная звезда'], $dto->getEntityData());
    }

    public function test_relations_data_is_empty(): void
    {
        $dto = new PublishingHouseDTO('Полярная звезда');

        $this->assertSame([], $dto->getRelationsData());
    }

    public function test_to_array_merges_entity_and_relations_data(): void
    {
        $dto = new PublishingHouseDTO('Полярная звезда');

        $this->assertSame([PublishingHouse::PARAM_NAME => 'Полярная звезда'], $dto->toArray());
    }
}
