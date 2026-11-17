<?php

namespace Tests\Unit\DTOs;

use App\DTOs\ProductPriceDTO;
use App\Models\ProductPrice;
use PHPUnit\Framework\TestCase;

/**
 * fromArray() кладёт дату в DTO как есть;
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

    public function test_entity_data_normalizes_date_to_iso8601_moscow(): void
    {
        $dto = new ProductPriceDTO(1200, '2026-01-01 00:00:00');

        $this->assertSame(
            [
                ProductPrice::PARAM_PRICE => 1200,
                ProductPrice::PARAM_DATE => '2026-01-01T00:00:00+03:00',
            ],
            $dto->getEntityData(),
        );
    }

    public function test_entity_data_normalizes_arbitrary_date_format(): void
    {
        // любой формат Carbon::parse-ится и уходит в upsert как ISO8601
        $dto = new ProductPriceDTO(500, '01.02.2026');

        $this->assertSame('2026-02-01T00:00:00+03:00', $dto->getEntityData()[ProductPrice::PARAM_DATE]);
    }
}
