<?php

namespace Tests\Feature\CacheProviders;

use App\CacheProviders\ShopsCacheProvider;
use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ShopsCacheProviderTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_and_get_shop_by_type(): void
    {
        Cache::flush();
        $type = 'shop-' . substr(uniqid(), -8);
        $shop = Shop::factory()->create([
            Shop::PARAM_TYPE => $type,
            Shop::PARAM_DOMAIN => 'd-' . substr(uniqid(), -6) . '.ru',
            Shop::PARAM_URL => 'https://example.ru',
        ]);

        $provider = $this->app->make(ShopsCacheProvider::class);
        $data = $provider->get();

        $this->assertTrue(collect($data)->contains(fn ($i) => ($i['id'] ?? null) === $shop->id && ($i['type'] ?? null) === $type));
        $this->assertSame($shop->id, $provider->getShopIdByType($type));
        $this->assertSame($shop->id, $provider->getShopByType($type)?->id);
    }
}
