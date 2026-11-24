<?php

namespace Tests\Unit\DTOs;

use App\DTOs\BookUserDataDTO;
use App\Models\BookUserData;
use Tests\TestCase;

class BookUserDataDTOTest extends TestCase
{
    public function test_normalizes_release_date(): void
    {
        $dto = BookUserDataDTO::fromArray([
            BookUserData::PARAM_BOOK_ID => 1,
            BookUserData::PARAM_RELEASE_DATE => '2026.01.15',
            BookUserData::PARAM_LISTEN_PRICE_VALUE => 350,
            BookUserData::PARAM_COMMENT => 'ok',
        ]);

        $data = $dto->getEntityData();

        $this->assertMatchesRegularExpression('/^2026-01-15 \d{2}:\d{2}:\d{2}$/', (string) $data[BookUserData::PARAM_RELEASE_DATE]);
        $this->assertSame(350, $data[BookUserData::PARAM_LISTEN_PRICE_VALUE]);
        $this->assertSame('ok', $data[BookUserData::PARAM_COMMENT]);
    }
}
