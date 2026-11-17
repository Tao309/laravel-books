<?php

namespace Tests\Unit\DTOs;

use App\DTOs\ProductPriceDTO;
use App\Models\ProductPrice;
use PHPUnit\Framework\TestCase;

/**
 * Даты НЕ прогоняются через DateService (в отличие от user_data-DTO) —
 * строка попадает в БД как есть.
 */
class ProductPriceDTOTest extends TestCase
{
    public function test_from_array_maps_price_and_date(): void
    {
        $dto = ProductPriceDTO::fromArray([
            ProductPrice::PARAM_PRICE => 1200,
            ProductPrice::PARAM_DATE => '2026-01-01 00:00:00',
        ]);

        $this->assertSame(1200, $dto->price);
        $this->assertSame('2026-01-01 00:00:00', $dto->date);
    }

    public function test_entity_data_passes_through_raw(): void
    {
        $dto = new ProductPriceDTO(1200, '2026-01-01 00:00:00');

        $this->assertSame(
            [
                ProductPrice::PARAM_PRICE => 1200,
                ProductPrice::PARAM_DATE => '2026-01-01 00:00:00',
            ],
            $dto->getEntityData(),
        );
    }

    public function test_entity_data_keeps_arbitrary_date_string(): void
    {
        // нет ни нормализации, ни валидации — любой формат уйдёт в upsert
        $dto = new ProductPriceDTO(500, '01.02.2026');

        $this->assertSame('01.02.2026', $dto->getEntityData()[ProductPrice::PARAM_DATE]);
    }
}
