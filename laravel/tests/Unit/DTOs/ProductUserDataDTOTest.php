<?php

namespace Tests\Unit\DTOs;

use App\DTOs\ProductUserDataDTO;
use App\Models\ProductUserData;
use Carbon\Exceptions\InvalidFormatException;
use PHPUnit\Framework\TestCase;

/**
 * fromArray задаёт дефолты available=true, isArchive=false.
 * Даты прогоняются через DateService::createFromDate (формат 'Y-m-d H:i:s') —
 * дефект формата 'Y.m.d' документирован и на API-уровне (ProductApiTest).
 */
class ProductUserDataDTOTest extends TestCase
{
    public function test_from_array_maps_fields(): void
    {
        $dto = ProductUserDataDTO::fromArray([
            ProductUserData::PARAM_PRODUCT_ID => 7,
            ProductUserData::PARAM_AVAILABLE => false,
            ProductUserData::PARAM_IS_ARCHIVE => true,
            ProductUserData::PARAM_NOT_AVAILABLE_DATE_FROM => '2026-11-10 12:00:00',
            ProductUserData::PARAM_AVAILABLE_DATE_FROM => '2026-11-11 12:00:00',
            ProductUserData::PARAM_LISTEN_PRICE_VALUE => 1500,
            ProductUserData::PARAM_LISTEN_QTY_VALUE => 3,
            ProductUserData::PARAM_RELEASE_DATE => '2026-12-01 00:00:00',
            ProductUserData::PARAM_COMMENT => 'Проверить комплектацию',
        ]);

        $this->assertSame(7, $dto->productId);
        $this->assertFalse($dto->available);
        $this->assertTrue($dto->isArchive);
        $this->assertSame(1500, $dto->listenPriceValue);
        $this->assertSame(3, $dto->listenQtyValue);
        $this->assertSame('Проверить комплектацию', $dto->comment);
    }

    public function test_from_array_defaults_available_true_and_archive_false(): void
    {
        $dto = ProductUserDataDTO::fromArray([ProductUserData::PARAM_PRODUCT_ID => 7]);

        $this->assertTrue($dto->available);
        $this->assertFalse($dto->isArchive);
        $this->assertNull($dto->notAvailableDateFrom);
        $this->assertNull($dto->comment);
    }

    public function test_entity_data_maps_booleans_and_formatted_dates(): void
    {
        $dto = new ProductUserDataDTO(
            productId: 7,
            available: false,
            isArchive: true,
            notAvailableDateFrom: '2026-11-10 12:00:00',
            availableDateFrom: null,
            listenPriceValue: 1500,
            listenQtyValue: null,
            releaseDate: '2026-12-01 00:00:00',
            comment: 'Комментарий',
        );

        $data = $dto->getEntityData();

        $this->assertFalse($data[ProductUserData::PARAM_AVAILABLE]);
        $this->assertTrue($data[ProductUserData::PARAM_IS_ARCHIVE]);
        $this->assertSame('2026-11-10 12:00:00', $data[ProductUserData::PARAM_NOT_AVAILABLE_DATE_FROM]);
        $this->assertNull($data[ProductUserData::PARAM_AVAILABLE_DATE_FROM]);
        $this->assertSame(1500, $data[ProductUserData::PARAM_LISTEN_PRICE_VALUE]);
        $this->assertNull($data[ProductUserData::PARAM_LISTEN_QTY_VALUE]);
        $this->assertSame('2026-12-01 00:00:00', $data[ProductUserData::PARAM_RELEASE_DATE]);
        $this->assertSame('Комментарий', $data[ProductUserData::PARAM_COMMENT]);
    }

    /**
     * Документация дефекта: точечный формат 'Y.m.d' не совпадает с
     * 'Y-m-d H:i:s' → Carbon::createFromFormat в strict-режиме (включён
     * в этом проекте) бросает InvalidFormatException вместо null,
     * поэтому до «Call to a member function format() on null» дело не доходит.
     * Проявляется как HTTP 500 в Product store (см. ProductApiTest).
     */
    public function test_entity_data_characterizes_dotted_format_fails_with_error(): void
    {
        $dto = new ProductUserDataDTO(
            productId: 7,
            available: true,
            isArchive: false,
            notAvailableDateFrom: '2026.11.10',
            availableDateFrom: null,
            listenPriceValue: null,
            listenQtyValue: null,
            releaseDate: null,
            comment: null,
        );

        $this->expectException(InvalidFormatException::class);

        $dto->getEntityData();
    }
}
