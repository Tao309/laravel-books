<?php

namespace Tests\Unit\DTOs;

use App\DTOs\PublishingBrandDTO;
use App\Models\PublishingBrand;
use PHPUnit\Framework\TestCase;

class PublishingBrandDTOTest extends TestCase
{
    public function test_from_array_maps_name(): void
    {
        $dto = PublishingBrandDTO::fromArray([
            PublishingBrand::PARAM_NAME => 'Красная звезда',
        ]);

        $this->assertSame('Красная звезда', $dto->name);
    }

    public function test_entity_data_contains_name(): void
    {
        $dto = new PublishingBrandDTO('Красная звезда');

        $this->assertSame([PublishingBrand::PARAM_NAME => 'Красная звезда'], $dto->getEntityData());
    }

    public function test_relations_data_is_empty(): void
    {
        $dto = new PublishingBrandDTO('Красная звезда');

        $this->assertSame([], $dto->getRelationsData());
    }

    public function test_to_array_merges_entity_and_relations_data(): void
    {
        $dto = new PublishingBrandDTO('Красная звезда');

        $this->assertSame([PublishingBrand::PARAM_NAME => 'Красная звезда'], $dto->toArray());
    }
}
