<?php

namespace Tests\Feature\CacheProviders;

use App\CacheProviders\ShopsCacheProvider;
use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * В phpunit.xml CACHE_STORE=array — кеш изолирован на каждый тест.
 * ShopsCacheProvider дополнительно даёт lookup по type — его использует
 * UserSecurity для маппинга заголовка shop-type на магазин.
 */
class ShopsCacheProviderTest extends TestCase
{
    use RefreshDatabase;

    private const string CACHE_KEY = 't_shops';

    private ShopsCacheProvider $provider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->provider = $this->app->make(ShopsCacheProvider::class);
    }

    public function test_get_returns_shops_from_db(): void
    {
        $shop = Shop::factory()->create([
            Shop::PARAM_URL => 'https://example.ru',
        ]);

        $data = $this->provider->get();

        $this->assertSame(
            [[
                'id' => $shop->id,
                'domain' => $shop->domain,
                'type' => $shop->type,
                'url' => 'https://example.ru',
            ]],
            $data,
        );
    }

    public function test_get_caches_data_under_provider_key(): void
    {
        Shop::factory()->create();

        $this->provider->get();

        $this->assertTrue(Cache::has(self::CACHE_KEY));
    }

    public function test_get_reuses_cached_payload(): void
    {
        // Перед первым get() таблица должна быть непустой: remember()
        // кеширует даже пустую выдачу, и тогда [] застрянет в кеше.
        Shop::factory()->create();

        $this->provider->get();

        // новая строка не попадёт в выдачу, пока кеш жив
        Shop::factory()->create();

        $this->assertCount(1, $this->provider->get());
    }

    public function test_delete_clears_cache(): void
    {
        Shop::factory()->create();

        $this->provider->get();
        $this->provider->delete();

        $this->assertFalse(Cache::has(self::CACHE_KEY));
    }

    public function test_get_shop_id_by_type_returns_matching_id(): void
    {
        $shop = Shop::factory()->create([Shop::PARAM_TYPE => 'ozon']);

        $this->assertSame($shop->id, $this->provider->getShopIdByType('ozon'));
    }

    public function test_get_shop_id_by_type_returns_null_for_unknown_type(): void
    {
        Shop::factory()->create([Shop::PARAM_TYPE => 'ozon']);

        $this->assertNull($this->provider->getShopIdByType('unknown-shop'));
    }

    public function test_get_shop_id_by_type_returns_null_for_empty_type(): void
    {
        $this->assertNull($this->provider->getShopIdByType(null));
    }

    public function test_get_shop_by_type_returns_shop_model_with_id(): void
    {
        $shop = Shop::factory()->create([
            Shop::PARAM_TYPE => 'ozon',
            Shop::PARAM_DOMAIN => 'ozon.ru',
            Shop::PARAM_URL => 'https://ozon.ru',
        ]);

        $found = $this->provider->getShopByType('ozon');

        $this->assertInstanceOf(Shop::class, $found);
        $this->assertSame($shop->id, $found->id);
        $this->assertSame('ozon', $found->type);
        $this->assertSame('ozon.ru', $found->domain);
        $this->assertSame('https://ozon.ru', $found->url);
    }

    public function test_get_shop_by_type_returns_null_for_unknown_type(): void
    {
        Shop::factory()->create([Shop::PARAM_TYPE => 'ozon']);

        $this->assertNull($this->provider->getShopByType('unknown-shop'));
    }
}
