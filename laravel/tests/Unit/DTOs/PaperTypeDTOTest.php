<?php

namespace Tests\Unit\DTOs;

use App\DTOs\PaperTypeDTO;
use App\Models\PaperType;
use PHPUnit\Framework\TestCase;

class PaperTypeDTOTest extends TestCase
{
    public function test_from_array_maps_label_and_description(): void
    {
        $dto = PaperTypeDTO::fromArray([
            PaperType::PARAM_LABEL => 'Мелованная',
            PaperType::PARAM_DESCRIPTION => 'Бумага с мелованным покрытием',
        ]);

        $this->assertSame('Мелованная', $dto->label);
        $this->assertSame('Бумага с мелованным покрытием', $dto->description);
    }

    /**
     * В отличие от AuthorDTO дефолт у Arr::get здесь — null, а не []:
     * опциональное поле обрабатывается корректно.
     */
    public function test_from_array_without_description_defaults_to_null(): void
    {
        $dto = PaperTypeDTO::fromArray([
            PaperType::PARAM_LABEL => 'Мелованная',
        ]);

        $this->assertSame('Мелованная', $dto->label);
        $this->assertNull($dto->description);
    }

    public function test_entity_data_contains_label_and_description(): void
    {
        $dto = new PaperTypeDTO('Мелованная', 'Бумага с мелованным покрытием');

        $this->assertSame([
            PaperType::PARAM_LABEL => 'Мелованная',
            PaperType::PARAM_DESCRIPTION => 'Бумага с мелованным покрытием',
        ], $dto->getEntityData());
    }

    public function test_relations_data_is_empty(): void
    {
        $dto = new PaperTypeDTO('Мелованная', null);

        $this->assertSame([], $dto->getRelationsData());
    }

    public function test_to_array_merges_entity_and_relations_data(): void
    {
        $dto = new PaperTypeDTO('Мелованная', null);

        $this->assertSame([
            PaperType::PARAM_LABEL => 'Мелованная',
            PaperType::PARAM_DESCRIPTION => null,
        ], $dto->toArray());
    }
}
