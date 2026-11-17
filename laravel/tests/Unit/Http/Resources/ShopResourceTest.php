<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\Shops\ShopResource;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * resolve() вызывается с явным Request, чтобы не дергать контейнер
 * (PHPUnit\Framework\TestCase без Laravel).
 */
class ShopResourceTest extends TestCase
{
    public function test_resolves_all_fields_and_timestamps(): void
    {
        $data = ShopResource::make($this->shop())->resolve(new Request);

        $this->assertSame(5, $data['id']);
        $this->assertSame('ozon', $data['type']);
        $this->assertSame('ozon.ru', $data['domain']);
        $this->assertSame('https://ozon.ru', $data['url']);
        $this->assertSame('2026-11-10 12:00:00', $data['updated_at']);
        $this->assertSame('2026-11-11 13:30:00', $data['created_at']);
    }

    public function test_url_is_null_when_not_set(): void
    {
        $shop = new Shop;
        $shop->id = 5;
        $shop->type = 'ozon';
        $shop->domain = 'ozon.ru';

        $data = ShopResource::make($shop)->resolve(new Request);

        $this->assertNull($data['url']);
    }

    public function test_timestamps_are_null_for_unpersisted_model(): void
    {
        $shop = new Shop;
        $shop->id = 5;
        $shop->type = 'ozon';
        $shop->domain = 'ozon.ru';

        $data = ShopResource::make($shop)->resolve(new Request);

        $this->assertNull($data['created_at']);
        $this->assertNull($data['updated_at']);
    }

    private function shop(): Shop
    {
        $shop = new Shop;
        $shop->id = 5;
        $shop->type = 'ozon';
        $shop->domain = 'ozon.ru';
        $shop->url = 'https://ozon.ru';
        $shop->created_at = Carbon::create(2026, 11, 11, 13, 30, 0);
        $shop->updated_at = Carbon::create(2026, 11, 10, 12, 0, 0);

        return $shop;
    }
}
