<?php

namespace Tests\Unit\DTOs;

use App\DTOs\ProductStockDTO;
use App\Models\ProductStock;
use PHPUnit\Framework\TestCase;

/**
 * Даты НЕ нормализуются, log должен быть строкой или null: массив
 * ловится типизированным свойством (TypeError). Строка уходит в upsert как есть.
 */
class ProductStockDTOTest extends TestCase
{
    public function test_from_array_maps_qty_date_and_log(): void
    {
        $dto = ProductStockDTO::fromArray([
            ProductStock::PARAM_QTY => 42,
            ProductStock::PARAM_DATE => '2026-01-01 00:00:00',
            ProductStock::PARAM_LOG => 'diffQty: -5',
        ]);

        $this->assertSame(42, $dto->qty);
        $this->assertSame('2026-01-01 00:00:00', $dto->date);
        $this->assertSame('diffQty: -5', $dto->log);
    }

    /**
     * Документация дефекта: массив вместо строки в log не сериализуется,
     * а падает TypeError в типизированном конструкторе.
     */
    public function test_from_array_with_array_log_throws_type_error(): void
    {
        $this->expectException(\TypeError::class);

        ProductStockDTO::fromArray([
            ProductStock::PARAM_QTY => 42,
            ProductStock::PARAM_DATE => '2026-01-01 00:00:00',
            ProductStock::PARAM_LOG => ['diffQty' => -5],
        ]);
    }

    public function test_from_array_defaults_log_to_null(): void
    {
        $dto = ProductStockDTO::fromArray([
            ProductStock::PARAM_QTY => 42,
            ProductStock::PARAM_DATE => '2026-01-01 00:00:00',
        ]);

        $this->assertNull($dto->log);
    }

    public function test_entity_data_passes_through_raw(): void
    {
        $dto = new ProductStockDTO(42, '2026-01-01 00:00:00', 'diffQty: 3');

        $this->assertSame(
            [
                ProductStock::PARAM_QTY => 42,
                ProductStock::PARAM_DATE => '2026-01-01 00:00:00',
                ProductStock::PARAM_LOG => 'diffQty: 3',
            ],
            $dto->getEntityData(),
        );
    }
}
