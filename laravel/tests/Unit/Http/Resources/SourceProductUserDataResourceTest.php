<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\SourceProductUserDatas\SourceProductUserDataResource;
use App\Models\SourceProductUserData;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class SourceProductUserDataResourceTest extends TestCase
{
    public function test_resolves_full_payload(): void
    {
        $data = SourceProductUserDataResource::make($this->userData())->resolve(new Request);

        $this->assertSame(1500, $data[SourceProductUserData::PARAM_LISTEN_PRICE_VALUE]);
        $this->assertSame('Нужен размер 43', $data[SourceProductUserData::PARAM_COMMENT]);
        $this->assertSame('2026-11-11 13:30:00', $data['created_at']);
        $this->assertSame('2026-11-09 09:00:00', $data['updated_at']);
    }

    public function test_null_fields_stay_null(): void
    {
        $data = SourceProductUserDataResource::make(new SourceProductUserData)->resolve(new Request);

        $this->assertNull($data[SourceProductUserData::PARAM_LISTEN_PRICE_VALUE]);
        $this->assertNull($data[SourceProductUserData::PARAM_COMMENT]);
        $this->assertNull($data['created_at']);
        $this->assertNull($data['updated_at']);
    }

    public function test_foreign_keys_are_not_exposed(): void
    {
        $data = SourceProductUserDataResource::make($this->userData())->resolve(new Request);

        $this->assertArrayNotHasKey(SourceProductUserData::PARAM_SOURCE_PRODUCT_ID, $data);
        $this->assertArrayNotHasKey(SourceProductUserData::PARAM_USER_ID, $data);
    }

    private function userData(): SourceProductUserData
    {
        $userData = new SourceProductUserData;
        $userData->listen_price_value = 1500;
        $userData->comment = 'Нужен размер 43';
        $userData->created_at = Carbon::create(2026, 11, 11, 13, 30, 0);
        $userData->updated_at = Carbon::create(2026, 11, 9, 9, 0, 0);

        return $userData;
    }
}
