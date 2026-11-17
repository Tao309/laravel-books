<?php

namespace Tests\Feature\CacheProviders;

use App\CacheProviders\PublishingBrandsCacheProvider;
use App\Models\PublishingBrand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * В phpunit.xml CACHE_STORE=array — кеш изолирован на каждый тест.
 * Имя ключа — защищённая константа провайдера, в тесте дублируется литералом.
 */
class PublishingBrandsCacheProviderTest extends TestCase
{
    use RefreshDatabase;

    private const string CACHE_KEY = 't_publishing_brands';

    public function test_get_returns_brands_from_db(): void
    {
        $brand = PublishingBrand::factory()->create();

        $data = $this->app->make(PublishingBrandsCacheProvider::class)->get();

        $this->assertSame(
            [['id' => $brand->id, 'name' => $brand->name]],
            $data,
        );
    }

    public function test_get_caches_data_under_provider_key(): void
    {
        PublishingBrand::factory()->create();

        $this->app->make(PublishingBrandsCacheProvider::class)->get();

        $this->assertTrue(Cache::has(self::CACHE_KEY));
    }

    public function test_get_reuses_cached_payload(): void
    {
        $provider = $this->app->make(PublishingBrandsCacheProvider::class);
        $provider->get();

        // новая строка не попадёт в выдачу, пока кеш жив
        PublishingBrand::factory()->create();

        $this->assertCount(1, $provider->get());
    }

    public function test_delete_clears_cache(): void
    {
        PublishingBrand::factory()->create();
        $provider = $this->app->make(PublishingBrandsCacheProvider::class);

        $provider->get();
        $provider->delete();

        $this->assertFalse(Cache::has(self::CACHE_KEY));
    }
}
