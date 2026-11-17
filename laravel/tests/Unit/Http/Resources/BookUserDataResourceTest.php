<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\BookUserDatas\BookUserDataResource;
use App\Models\BookUserData;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * resolve() вызывается с явным Request, чтобы не дергать контейнер
 * (PHPUnit\Framework\TestCase без Laravel).
 */
class BookUserDataResourceTest extends TestCase
{
    public function test_resolves_full_payload(): void
    {
        $data = BookUserDataResource::make($this->userData())->resolve(new Request);

        $this->assertSame('2026-11-10 12:00:00', $data[BookUserData::PARAM_RELEASE_DATE]);
        $this->assertSame(1500, $data[BookUserData::PARAM_LISTEN_PRICE_VALUE]);
        $this->assertSame('Дождаться скидки', $data[BookUserData::PARAM_COMMENT]);
        $this->assertSame('2026-11-11 13:30:00', $data['created_at']);
        $this->assertSame('2026-11-09 09:00:00', $data['updated_at']);
    }

    public function test_release_date_stays_null_when_not_set(): void
    {
        $userData = new BookUserData;
        $userData->listen_price_value = 1500;

        $data = BookUserDataResource::make($userData)->resolve(new Request);

        $this->assertNull($data[BookUserData::PARAM_RELEASE_DATE]);
        $this->assertNull($data['created_at']);
        $this->assertNull($data['updated_at']);
    }

    public function test_foreign_keys_are_not_exposed(): void
    {
        $data = BookUserDataResource::make($this->userData())->resolve(new Request);

        $this->assertArrayNotHasKey(BookUserData::PARAM_BOOK_ID, $data);
        $this->assertArrayNotHasKey(BookUserData::PARAM_USER_ID, $data);
    }

    private function userData(): BookUserData
    {
        $userData = new BookUserData;
        $userData->release_date = Carbon::create(2026, 11, 10, 12, 0, 0);
        $userData->listen_price_value = 1500;
        $userData->comment = 'Дождаться скидки';
        $userData->created_at = Carbon::create(2026, 11, 11, 13, 30, 0);
        $userData->updated_at = Carbon::create(2026, 11, 9, 9, 0, 0);

        return $userData;
    }
}
