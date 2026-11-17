<?php

namespace Tests\Unit\DTOs;

use App\DTOs\BookUserDataDTO;
use App\Models\BookUserData;
use Carbon\Exceptions\InvalidFormatException;
use PHPUnit\Framework\TestCase;

/**
 * Дефект формата дат (та же семья, что в ProductUserDataDTO):
 * DateService::createFromDate ждёт 'Y-m-d H:i:s'; короткий 'Y-m-d'
 * роняет Carbon 3 исключением InvalidFormatException.
 */
class BookUserDataDTOTest extends TestCase
{
    public function test_from_array_maps_fields(): void
    {
        $dto = BookUserDataDTO::fromArray([
            BookUserData::PARAM_BOOK_ID => 3,
            BookUserData::PARAM_RELEASE_DATE => '2026-11-10 12:00:00',
            BookUserData::PARAM_LISTEN_PRICE_VALUE => 1500,
            BookUserData::PARAM_COMMENT => 'Дождаться скидки',
        ]);

        $this->assertSame(3, $dto->bookId);
        $this->assertSame('2026-11-10 12:00:00', $dto->releaseDate);
        $this->assertSame(1500, $dto->listenPriceValue);
        $this->assertSame('Дождаться скидки', $dto->comment);
    }

    public function test_from_array_defaults_to_nulls(): void
    {
        $dto = BookUserDataDTO::fromArray([BookUserData::PARAM_BOOK_ID => 3]);

        $this->assertNull($dto->releaseDate);
        $this->assertNull($dto->listenPriceValue);
        $this->assertNull($dto->comment);
    }

    public function test_entity_data_converts_release_date_to_formatted_string(): void
    {
        $dto = new BookUserDataDTO(3, '2026-11-10 12:00:00', 1500, 'Дождаться скидки');

        $data = $dto->getEntityData();

        $this->assertSame(
            '2026-11-10 12:00:00',
            $data[BookUserData::PARAM_RELEASE_DATE],
        );
        $this->assertSame(1500, $data[BookUserData::PARAM_LISTEN_PRICE_VALUE]);
        $this->assertSame('Дождаться скидки', $data[BookUserData::PARAM_COMMENT]);
    }

    public function test_entity_data_keeps_null_release_date(): void
    {
        $dto = new BookUserDataDTO(3, null, null, null);

        $this->assertNull($dto->getEntityData()[BookUserData::PARAM_RELEASE_DATE]);
    }

    /**
     * Документация дефекта: DateService::createFromDate ждёт 'Y-m-d H:i:s',
     * короткая 'Y-m-d' не «молча дополняется», а роняет Carbon 3 исключением
     * InvalidFormatException (Not enough data available to satisfy format).
     */
    public function test_entity_data_characterizes_short_date_format_throws_invalid_format(): void
    {
        $dto = new BookUserDataDTO(3, '2026-11-10', 1500, null);

        $this->expectException(InvalidFormatException::class);

        $dto->getEntityData();
    }
}
