<?php

namespace Tests\Unit\DTOs;

use App\DTOs\ProductPriceDTO;
use App\Models\ProductPrice;
use Tests\TestCase;

class ProductPriceDTOTest extends TestCase
{
    public function test_normalizes_date(): void
    {
        $data = ProductPriceDTO::fromArray([
            ProductPrice::PARAM_PRICE => 100,
            ProductPrice::PARAM_DATE => '2026.11.01',
        ])->getEntityData();

        $this->assertSame(100, $data[ProductPrice::PARAM_PRICE]);
        $this->assertMatchesRegularExpression('/^2026-11-01 \d{2}:\d{2}:\d{2}$/', (string) $data[ProductPrice::PARAM_DATE]);
    }
}
