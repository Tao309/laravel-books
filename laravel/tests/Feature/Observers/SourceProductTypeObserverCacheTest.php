<?php

namespace Tests\Feature\Observers;

use App\CacheProviders\SourceProductsTypesCacheProvider;
use App\Models\SourceProductType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * Интеграция: #[ObservedBy] на модели действительно навешивает обсервер,
 * и события created/updated/deleted сбрасывают кеш справочника.
 * Покрытие самих обработчиков — в Tests\Unit\Observers\SourceProductTypeObserverTest.
 */
class SourceProductTypeObserverCacheTest extends TestCase
{
    use RefreshDatabase;

    private const string CACHE_KEY = 't_source_product_types';

    private SourceProductsTypesCacheProvider $cacheProvider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cacheProvider = $this->app->make(SourceProductsTypesCacheProvider::class);
    }

    public function test_creating_type_clears_cache(): void
    {
        $this->warmUpCache();

        SourceProductType::factory()->create();

        $this->assertFalse(Cache::has(self::CACHE_KEY));
    }

    public function test_updating_type_clears_cache(): void
    {
        $type = SourceProductType::factory()->create();
        $this->warmUpCache();

        $type->update([SourceProductType::PARAM_NAME => 'Обновлённый тип']);

        $this->assertFalse(Cache::has(self::CACHE_KEY));
    }

    public function test_deleting_type_clears_cache(): void
    {
        $type = SourceProductType::factory()->create();
        $this->warmUpCache();

        $type->delete();

        $this->assertFalse(Cache::has(self::CACHE_KEY));
    }

    private function warmUpCache(): void
    {
        $this->cacheProvider->get();

        $this->assertTrue(
            Cache::has(self::CACHE_KEY),
            'Кеш должен быть прогрет перед проверкой инвалидации',
        );
    }
}
