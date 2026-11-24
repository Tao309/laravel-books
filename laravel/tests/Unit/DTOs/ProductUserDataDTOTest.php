<?php

namespace Tests\Unit\DTOs;

use App\DTOs\ProductUserDataDTO;
use App\Models\ProductUserData;
use Tests\TestCase;

class ProductUserDataDTOTest extends TestCase
{
    public function test_defaults_and_normalizes_dates(): void
    {
        $dto = ProductUserDataDTO::fromArray([
            ProductUserData::PARAM_PRODUCT_ID => 1,
            ProductUserData::PARAM_AVAILABLE_DATE_FROM => '2026.11.01',
        ]);

        $data = $dto->getEntityData();

        $this->assertTrue($data[ProductUserData::PARAM_AVAILABLE]);
        $this->assertFalse($data[ProductUserData::PARAM_IS_ARCHIVE]);
        $this->assertMatchesRegularExpression('/^2026-11-01 \d{2}:\d{2}:\d{2}$/', (string) $data[ProductUserData::PARAM_AVAILABLE_DATE_FROM]);
    }
}
