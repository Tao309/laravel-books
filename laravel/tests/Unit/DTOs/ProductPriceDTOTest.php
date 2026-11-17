<?php

namespace Tests\Unit\DTOs;

use App\DTOs\ProductPriceDTO;
use App\Models\ProductPrice;
use PHPUnit\Framework\TestCase;

/**
 * Документация дефекта: даты НЕ прогоняются через DateService
 * (там есть prepareForSave, но DTO его не вызывает) — строка попадает
 * в БД как есть. Следствие: одна и та же дата в разных форматах
 * ('2026-01-01 10:00:00' и '2026-01-01T10:00:00+03:00') НЕ коллайдется
 * в уникальном индексе (product_id, user_id, date) и дублируется.
 * Если нормализацию вернут (forSave/prepareForSave в getEntityData) —
 * тесты нужно перевернуть на ISO8601+03:00.
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
        // документация дефекта: формат не парсится и не нормализуется
        $dto = new ProductPriceDTO(500, '01.02.2026');

        $this->assertSame('01.02.2026', $dto->getEntityData()[ProductPrice::PARAM_DATE]);
    }
}
