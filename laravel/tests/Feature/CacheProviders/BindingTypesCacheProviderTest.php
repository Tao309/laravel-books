<?php

namespace Tests\Feature\CacheProviders;

use App\CacheProviders\BindingTypesCacheProvider;
use App\Models\BindingType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * В phpunit.xml CACHE_STORE=array — кеш изолирован на каждый тест.
 * Имя ключа — защищённая константа провайдера, в тесте дублируется литералом.
 */
class BindingTypesCacheProviderTest extends TestCase
{
    use RefreshDatabase;

    private const string CACHE_KEY = 't_binding_types';

    public function test_get_returns_binding_types_from_db(): void
    {
        $bindingType = BindingType::factory()->create();

        $data = $this->app->make(BindingTypesCacheProvider::class)->get();

        $this->assertSame(
            [['id' => $bindingType->id, 'label' => $bindingType->label]],
            $data,
        );
    }

    public function test_get_caches_data_under_provider_key(): void
    {
        BindingType::factory()->create();

        $this->app->make(BindingTypesCacheProvider::class)->get();

        $this->assertTrue(Cache::has(self::CACHE_KEY));
    }

    public function test_get_reuses_cached_payload(): void
    {
        $provider = $this->app->make(BindingTypesCacheProvider::class);
        $provider->get();

        // новая строка не попадёт в выдачу, пока кеш жив
        BindingType::factory()->create();

        $this->assertCount(1, $provider->get());
    }

    public function test_delete_clears_cache(): void
    {
        BindingType::factory()->create();
        $provider = $this->app->make(BindingTypesCacheProvider::class);

        $provider->get();
        $provider->delete();

        $this->assertFalse(Cache::has(self::CACHE_KEY));
    }
}
