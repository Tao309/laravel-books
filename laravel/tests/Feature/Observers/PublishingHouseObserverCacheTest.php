<?php

namespace Tests\Feature\Observers;

use App\CacheProviders\PublishingHousesCacheProvider;
use App\Models\PublishingHouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * Интеграция: #[ObservedBy] на модели действительно навешивает обсервер,
 * и события created/updated/deleted сбрасывают кеш справочника.
 * Покрытие самих обработчиков — в Tests\Unit\Observers\PublishingHouseObserverTest.
 */
class PublishingHouseObserverCacheTest extends TestCase
{
    use RefreshDatabase;

    private const string CACHE_KEY = 't_publishing_houses';

    private PublishingHousesCacheProvider $cacheProvider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cacheProvider = $this->app->make(PublishingHousesCacheProvider::class);
    }

    public function test_creating_house_clears_cache(): void
    {
        $this->warmUpCache();

        PublishingHouse::factory()->create();

        $this->assertFalse(Cache::has(self::CACHE_KEY));
    }

    public function test_updating_house_clears_cache(): void
    {
        $house = PublishingHouse::factory()->create();
        $this->warmUpCache();

        $house->update([PublishingHouse::PARAM_NAME => 'Обновлённое имя издательства']);

        $this->assertFalse(Cache::has(self::CACHE_KEY));
    }

    public function test_deleting_house_clears_cache(): void
    {
        $house = PublishingHouse::factory()->create();
        $this->warmUpCache();

        $house->delete();

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
