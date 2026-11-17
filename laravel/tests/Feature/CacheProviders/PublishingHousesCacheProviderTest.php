<?php

namespace Tests\Feature\CacheProviders;

use App\CacheProviders\PublishingHousesCacheProvider;
use App\Models\PublishingHouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * В phpunit.xml CACHE_STORE=array — кеш изолирован на каждый тест.
 * Имя ключа — защищённая константа провайдера, в тесте дублируется литералом.
 */
class PublishingHousesCacheProviderTest extends TestCase
{
    use RefreshDatabase;

    private const string CACHE_KEY = 't_publishing_houses';

    public function test_get_returns_houses_from_db(): void
    {
        $house = PublishingHouse::factory()->create();

        $data = $this->app->make(PublishingHousesCacheProvider::class)->get();

        $this->assertSame(
            [['id' => $house->id, 'name' => $house->name]],
            $data,
        );
    }

    public function test_get_caches_data_under_provider_key(): void
    {
        PublishingHouse::factory()->create();

        $this->app->make(PublishingHousesCacheProvider::class)->get();

        $this->assertTrue(Cache::has(self::CACHE_KEY));
    }

    public function test_get_reuses_cached_payload(): void
    {
        $provider = $this->app->make(PublishingHousesCacheProvider::class);
        $provider->get();

        // новая строка не попадёт в выдачу, пока кеш жив
        PublishingHouse::factory()->create();

        $this->assertCount(1, $provider->get());
    }

    public function test_delete_clears_cache(): void
    {
        PublishingHouse::factory()->create();
        $provider = $this->app->make(PublishingHousesCacheProvider::class);

        $provider->get();
        $provider->delete();

        $this->assertFalse(Cache::has(self::CACHE_KEY));
    }
}
