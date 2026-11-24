<?php

namespace Tests\Unit\DTOs;

use App\DTOs\SourceProductUserDataDTO;
use App\Models\SourceProductUserData;
use PHPUnit\Framework\TestCase;

class SourceProductUserDataDTOTest extends TestCase
{
    public function test_from_array_maps_entity_data(): void
    {
        $dto = SourceProductUserDataDTO::fromArray([
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => 1,
            SourceProductUserData::PARAM_LISTEN_PRICE_VALUE => 123,
            SourceProductUserData::PARAM_COMMENT => 'text',
        ]);

        $this->assertSame([
            SourceProductUserData::PARAM_LISTEN_PRICE_VALUE => 123,
            SourceProductUserData::PARAM_COMMENT => 'text',
        ], $dto->getEntityData());
    }
}
