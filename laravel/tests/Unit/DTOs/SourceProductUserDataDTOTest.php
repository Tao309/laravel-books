<?php

namespace Tests\Unit\DTOs;

use App\DTOs\SourceProductUserDataDTO;
use App\Models\SourceProductUserData;
use PHPUnit\Framework\TestCase;

class SourceProductUserDataDTOTest extends TestCase
{
    public function test_from_array_maps_fields(): void
    {
        $dto = SourceProductUserDataDTO::fromArray([
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => 4,
            SourceProductUserData::PARAM_LISTEN_PRICE_VALUE => 1500,
            SourceProductUserData::PARAM_COMMENT => 'Нужен размер 43',
        ]);

        $this->assertSame(4, $dto->sourceProductId);
        $this->assertSame(1500, $dto->listenPriceValue);
        $this->assertSame('Нужен размер 43', $dto->comment);
    }

    public function test_from_array_allows_nulls(): void
    {
        $dto = SourceProductUserDataDTO::fromArray([
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => 4,
        ]);

        $this->assertNull($dto->listenPriceValue);
        $this->assertNull($dto->comment);
    }

    public function test_entity_data_contains_only_user_fields(): void
    {
        $dto = new SourceProductUserDataDTO(4, 1500, 'Нужен размер 43');

        $this->assertSame(
            [
                SourceProductUserData::PARAM_LISTEN_PRICE_VALUE => 1500,
                SourceProductUserData::PARAM_COMMENT => 'Нужен размер 43',
            ],
            $dto->getEntityData(),
        );
    }

    public function test_entity_data_does_not_include_source_product_id(): void
    {
        // key связки добавляет сам репозиторий — DTO не несёт source_product_id
        $dto = new SourceProductUserDataDTO(4, null, null);

        $this->assertArrayNotHasKey(
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID,
            $dto->getEntityData(),
        );
    }
}
