<?php

namespace Tests\Unit\DTOs;

use App\DTOs\SourceProductTypeDTO;
use App\Models\SourceProductType;
use PHPUnit\Framework\TestCase;

class SourceProductTypeDTOTest extends TestCase
{
    public function test_from_array_maps_code_and_name(): void
    {
        $dto = SourceProductTypeDTO::fromArray([
            SourceProductType::PARAM_CODE => 'shoes',
            SourceProductType::PARAM_NAME => 'Обувь',
        ]);

        $this->assertSame('shoes', $dto->code);
        $this->assertSame('Обувь', $dto->name);
    }

    public function test_entity_data_contains_code_and_name(): void
    {
        $dto = new SourceProductTypeDTO('shoes', 'Обувь');

        $this->assertSame([
            SourceProductType::PARAM_CODE => 'shoes',
            SourceProductType::PARAM_NAME => 'Обувь',
        ], $dto->getEntityData());
    }

    public function test_relations_data_is_empty(): void
    {
        $dto = new SourceProductTypeDTO('shoes', 'Обувь');

        $this->assertSame([], $dto->getRelationsData());
    }

    public function test_to_array_merges_entity_and_relations_data(): void
    {
        $dto = new SourceProductTypeDTO('shoes', 'Обувь');

        $this->assertSame([
            SourceProductType::PARAM_CODE => 'shoes',
            SourceProductType::PARAM_NAME => 'Обувь',
        ], $dto->toArray());
    }
}
