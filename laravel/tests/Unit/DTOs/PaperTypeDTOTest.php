<?php

namespace Tests\Unit\DTOs;

use App\DTOs\PaperTypeDTO;
use App\Models\PaperType;
use PHPUnit\Framework\TestCase;

class PaperTypeDTOTest extends TestCase
{
    public function test_from_array_maps_entity_data(): void
    {
        $dto = PaperTypeDTO::fromArray([PaperType::PARAM_LABEL => '?????']);
        $data = $dto->getEntityData();
        $this->assertSame('?????', $data[PaperType::PARAM_LABEL]);
        $this->assertArrayHasKey(PaperType::PARAM_DESCRIPTION, $data);
    }
}
