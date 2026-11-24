<?php

namespace Tests\Unit\DTOs;

use App\DTOs\ProductStockDTO;
use App\Models\ProductStock;
use Tests\TestCase;

class ProductStockDTOTest extends TestCase
{
    public function test_normalizes_date_and_maps_qty(): void
    {
        $data = ProductStockDTO::fromArray([
            ProductStock::PARAM_QTY => 2,
            ProductStock::PARAM_DATE => '2026.11.01',
            ProductStock::PARAM_LOG => ['a' => 1],
        ])->getEntityData();

        $this->assertSame(2, $data[ProductStock::PARAM_QTY]);
        $this->assertMatchesRegularExpression('/^2026-11-01 \d{2}:\d{2}:\d{2}$/', (string) $data[ProductStock::PARAM_DATE]);
    }
}
