<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\ProductUserDatas\ProductUserDataResource;
use App\Models\ProductUserData;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * Ресурс приводит available/is_archive к bool и форматирует даты.
 */
class ProductUserDataResourceTest extends TestCase
{
    public function test_resolves_full_payload(): void
    {
        $data = ProductUserDataResource::make($this->userData())->resolve(new Request);

        $this->assertTrue($data[ProductUserData::PARAM_AVAILABLE]);
        $this->assertFalse($data[ProductUserData::PARAM_IS_ARCHIVE]);
        $this->assertSame('2026-11-10 12:00:00', $data[ProductUserData::PARAM_NOT_AVAILABLE_DATE_FROM]);
        $this->assertSame('2026-11-11 12:00:00', $data[ProductUserData::PARAM_AVAILABLE_DATE_FROM]);
        $this->assertSame(1500, $data[ProductUserData::PARAM_LISTEN_PRICE_VALUE]);
        $this->assertSame(3, $data[ProductUserData::PARAM_LISTEN_QTY_VALUE]);
        $this->assertSame('2026-12-01 00:00:00', $data[ProductUserData::PARAM_RELEASE_DATE]);
        $this->assertSame('Проверить комплектацию', $data[ProductUserData::PARAM_COMMENT]);
        $this->assertSame('2026-11-09 09:00:00', $data['created_at']);
        $this->assertSame('2026-11-12 15:00:00', $data['updated_at']);
    }

    public function test_null_dates_stay_null_and_booleans_coerce_to_false(): void
    {
        $userData = new ProductUserData;
        $userData->comment = 'Только комментарий';

        $data = ProductUserDataResource::make($userData)->resolve(new Request);

        $this->assertFalse($data[ProductUserData::PARAM_AVAILABLE]);
        $this->assertFalse($data[ProductUserData::PARAM_IS_ARCHIVE]);
        $this->assertNull($data[ProductUserData::PARAM_NOT_AVAILABLE_DATE_FROM]);
        $this->assertNull($data[ProductUserData::PARAM_AVAILABLE_DATE_FROM]);
        $this->assertNull($data[ProductUserData::PARAM_RELEASE_DATE]);
        $this->assertNull($data['created_at']);
        $this->assertNull($data['updated_at']);
    }

    public function test_foreign_keys_are_not_exposed(): void
    {
        $data = ProductUserDataResource::make($this->userData())->resolve(new Request);

        $this->assertArrayNotHasKey(ProductUserData::PARAM_PRODUCT_ID, $data);
        $this->assertArrayNotHasKey(ProductUserData::PARAM_USER_ID, $data);
    }

    private function userData(): ProductUserData
    {
        $userData = new ProductUserData;
        $userData->available = true;
        $userData->is_archive = false;
        $userData->not_available_date_from = Carbon::create(2026, 11, 10, 12, 0, 0);
        $userData->available_date_from = Carbon::create(2026, 11, 11, 12, 0, 0);
        $userData->listen_price_value = 1500;
        $userData->listen_qty_value = 3;
        $userData->release_date = Carbon::create(2026, 12, 1, 0, 0, 0);
        $userData->comment = 'Проверить комплектацию';
        $userData->created_at = Carbon::create(2026, 11, 9, 9, 0, 0);
        $userData->updated_at = Carbon::create(2026, 11, 12, 15, 0, 0);

        return $userData;
    }
}
