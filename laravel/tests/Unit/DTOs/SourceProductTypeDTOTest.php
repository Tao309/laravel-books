<?php

namespace Tests\Unit\DTOs;

use App\DTOs\SourceProductTypeDTO;
use App\Models\SourceProductType;
use PHPUnit\Framework\TestCase;

class SourceProductTypeDTOTest extends TestCase
{
    public function test_from_array_maps_entity_data(): void
    {
        $dto = SourceProductTypeDTO::fromArray([SourceProductType::PARAM_NAME => '???', SourceProductType::PARAM_CODE => 'type']);
        $this->assertSame([SourceProductType::PARAM_CODE => 'type', SourceProductType::PARAM_NAME => '???'], $dto->getEntityData());
    }
}
