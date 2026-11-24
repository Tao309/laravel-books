<?php

namespace Tests\Unit\DTOs;

use App\DTOs\PublishingHouseDTO;
use App\Models\PublishingHouse;
use PHPUnit\Framework\TestCase;

class PublishingHouseDTOTest extends TestCase
{
    public function test_from_array_maps_entity_data(): void
    {
        $dto = PublishingHouseDTO::fromArray([PublishingHouse::PARAM_NAME => '???']);
        $this->assertSame([PublishingHouse::PARAM_NAME => '???'], $dto->getEntityData());
    }
}
