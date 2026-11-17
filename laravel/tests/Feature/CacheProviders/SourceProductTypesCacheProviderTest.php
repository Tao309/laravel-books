<?php

namespace Tests\Feature\CacheProviders;

use App\CacheProviders\SourceProductsTypesCacheProvider;
use App\Models\SourceProductType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * В phpunit.xml CACHE_STORE=array — кеш изолирован на каждый тест.
 * Имя ключа — защищённая константа провайдера, в тесте дублируется литералом.
 * Питает source_product_types в /api/config.
 */
class SourceProductTypesCacheProviderTest extends TestCase
{
    use RefreshDatabase;

    private const string CACHE_KEY = 't_source_product_types';

    public function test_get_returns_types_with_code_and_name(): void
    {
        $type = SourceProductType::factory()->create();

        $data = $this->app->make(SourceProductsTypesCacheProvider::class)->get();

        $this->assertSame(
            [[
                'id' => $type->id,
                'code' => $type->code,
                'name' => $type->name,
            ]],
            $data,
        );
    }

    public function test_get_caches_data_under_provider_key(): void
    {
        SourceProductType::factory()->create();

        $this->app->make(SourceProductsTypesCacheProvider::class)->get();

        $this->assertTrue(Cache::has(self::CACHE_KEY));
    }

    public function test_get_reuses_cached_payload(): void
    {
        $provider = $this->app->make(SourceProductsTypesCacheProvider::class);
        $provider->get();

        // новая строка не попадёт в выдачу, пока кеш жив
        SourceProductType::factory()->create();

        $this->assertCount(1, $provider->get());
    }

    public function test_delete_clears_cache(): void
    {
        SourceProductType::factory()->create();
        $provider = $this->app->make(SourceProductsTypesCacheProvider::class);

        $provider->get();
        $provider->delete();

        $this->assertFalse(Cache::has(self::CACHE_KEY));
    }
}
