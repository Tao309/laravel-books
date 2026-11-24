<?php

namespace Tests\Unit\DTOs;

use App\DTOs\CityDTO;
use App\Models\City;
use PHPUnit\Framework\TestCase;

class CityDTOTest extends TestCase
{
    public function test_from_array_maps_entity_data(): void
    {
        $dto = CityDTO::fromArray([City::PARAM_CODE => 'msk', City::PARAM_TITLE => '??????']);
        $this->assertSame([City::PARAM_CODE => 'msk', City::PARAM_TITLE => '??????'], $dto->getEntityData());
        $this->assertSame([], $dto->getRelationsData());
    }
}
