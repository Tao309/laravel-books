<?php

namespace Tests\Feature\Observers;

use App\CacheProviders\ShopsCacheProvider;
use App\Models\Shop;
use App\Observers\ShopObserver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ShopObserverCacheTest extends TestCase
{
    use RefreshDatabase;

    public function test_observer_invalidates_cache_on_create(): void
    {
        $provider = $this->makeProvider();
        $before = count($provider->get());

        $this->createShop();

        $this->assertGreaterThanOrEqual($before + 1, count($provider->get()));
    }

    public function test_observer_invalidates_cache_on_update(): void
    {
        $shop = $this->createShop();
        $provider = $this->makeProvider();
        $provider->get();
        $domain = 'upd-' . substr(uniqid(), -8) . '.example.com';

        $shop->update([Shop::PARAM_DOMAIN => $domain]);

        $this->assertSame($domain, $this->findCachedDomain($provider, $shop->id));
    }

    public function test_observer_invalidates_cache_on_delete(): void
    {
        $shop = $this->createShop();
        $provider = $this->makeProvider();
        $before = count($provider->get());

        $shop->delete();

        $this->assertSame($before - 1, count($provider->get()));
        $this->assertNull($this->findCachedDomain($provider, $shop->id));
    }

    public function test_observer_invalidates_cache_on_restored(): void
    {
        $shop = $this->createShop();
        $provider = $this->makeProvider();
        $provider->get();
        Cache::put('t_shops', json_encode([]), 3600 * 24 * 30);

        $this->app->make(ShopObserver::class)->restored();

        $this->assertSame($shop->domain, $this->findCachedDomain($provider, $shop->id));
    }

    public function test_observer_invalidates_cache_on_force_deleted(): void
    {
        $shop = $this->createShop();
        $provider = $this->makeProvider();
        $provider->get();
        Cache::put('t_shops', json_encode([$shop->toArray()]), 3600 * 24 * 30);
        $shopId = $shop->id;
        $shop->deleteQuietly();

        $this->app->make(ShopObserver::class)->forceDeleted();

        $this->assertNull($this->findCachedDomain($provider, $shopId));
    }

    private function makeProvider(): ShopsCacheProvider
    {
        Cache::flush();

        return $this->app->make(ShopsCacheProvider::class);
    }

    private function createShop(): Shop
    {
        return Shop::factory()->create([
            Shop::PARAM_TYPE => 'obs-' . substr(uniqid(), -8),
            Shop::PARAM_DOMAIN => 'obs-' . substr(uniqid(), -8) . '.example.com',
        ]);
    }

    private function findCachedDomain(ShopsCacheProvider $provider, int $shopId): ?string
    {
        foreach ($provider->get() as $shopData) {
            if ($shopData[Shop::PARAM_ID] === $shopId) {
                return $shopData[Shop::PARAM_DOMAIN];
            }
        }

        return null;
    }
}
