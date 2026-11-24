<?php

namespace Tests\Unit\DTOs;

use App\DTOs\PublishingBrandDTO;
use App\Models\PublishingBrand;
use PHPUnit\Framework\TestCase;

class PublishingBrandDTOTest extends TestCase
{
    public function test_from_array_maps_entity_data(): void
    {
        $dto = PublishingBrandDTO::fromArray([PublishingBrand::PARAM_NAME => '?????']);
        $this->assertSame([PublishingBrand::PARAM_NAME => '?????'], $dto->getEntityData());
    }
}
