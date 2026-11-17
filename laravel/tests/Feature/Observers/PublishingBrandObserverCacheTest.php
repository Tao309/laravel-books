<?php

namespace Tests\Feature\Observers;

use App\CacheProviders\PublishingBrandsCacheProvider;
use App\Models\PublishingBrand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * Интеграция: #[ObservedBy] на модели действительно навешивает обсервер,
 * и события created/updated/deleted сбрасывают кеш справочника.
 * Покрытие самих обработчиков — в Tests\Unit\Observers\PublishingBrandObserverTest.
 */
class PublishingBrandObserverCacheTest extends TestCase
{
    use RefreshDatabase;

    private const string CACHE_KEY = 't_publishing_brands';

    private PublishingBrandsCacheProvider $cacheProvider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cacheProvider = $this->app->make(PublishingBrandsCacheProvider::class);
    }

    public function test_creating_brand_clears_cache(): void
    {
        $this->warmUpCache();

        PublishingBrand::factory()->create();

        $this->assertFalse(Cache::has(self::CACHE_KEY));
    }

    public function test_updating_brand_clears_cache(): void
    {
        $brand = PublishingBrand::factory()->create();
        $this->warmUpCache();

        $brand->update([PublishingBrand::PARAM_NAME => 'Обновлённое имя бренда']);

        $this->assertFalse(Cache::has(self::CACHE_KEY));
    }

    public function test_deleting_brand_clears_cache(): void
    {
        $brand = PublishingBrand::factory()->create();
        $this->warmUpCache();

        $brand->delete();

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
