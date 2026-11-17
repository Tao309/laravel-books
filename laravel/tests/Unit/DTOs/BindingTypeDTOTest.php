<?php

namespace Tests\Unit\DTOs;

use App\DTOs\BindingTyperDTO;
use App\Models\BindingType;
use PHPUnit\Framework\TestCase;

class BindingTypeDTOTest extends TestCase
{
    public function test_from_array_maps_label(): void
    {
        $dto = BindingTyperDTO::fromArray([
            BindingType::PARAM_LABEL => 'Твёрдый переплёт',
        ]);

        $this->assertSame('Твёрдый переплёт', $dto->label);
    }

    public function test_entity_data_contains_label(): void
    {
        $dto = new BindingTyperDTO('Твёрдый переплёт');

        $this->assertSame(
            [BindingType::PARAM_LABEL => 'Твёрдый переплёт'],
            $dto->getEntityData(),
        );
    }

    public function test_relations_data_is_empty(): void
    {
        $dto = new BindingTyperDTO('Твёрдый переплёт');

        $this->assertSame([], $dto->getRelationsData());
    }

    public function test_to_array_merges_entity_and_relations_data(): void
    {
        $dto = new BindingTyperDTO('Твёрдый переплёт');

        $this->assertSame(
            [BindingType::PARAM_LABEL => 'Твёрдый переплёт'],
            $dto->toArray(),
        );
    }
}
