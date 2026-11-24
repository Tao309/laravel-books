<?php

namespace Tests\Unit\DTOs;

use App\DTOs\SourceProductDTO;
use App\Models\SourceProduct;
use App\Models\SourceProductType;
use PHPUnit\Framework\TestCase;

class SourceProductDTOTest extends TestCase
{
    public function test_from_array_maps_data(): void
    {
        $dto = SourceProductDTO::fromArray([
            SourceProduct::PARAM_TITLE => 'SP',
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE => [SourceProductType::PARAM_ID => 5],
        ]);

        $this->assertSame('SP', $dto->getEntityData()[SourceProduct::PARAM_TITLE]);
        $this->assertSame(5, $dto->getRelationsData()[SourceProduct::PARAM_SOURCE_PRODUCT_TYPE]);
    }
}
