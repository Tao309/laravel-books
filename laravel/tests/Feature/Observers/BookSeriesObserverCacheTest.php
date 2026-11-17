<?php

namespace Tests\Feature\Observers;

use App\CacheProviders\BookSeriesCacheProvider;
use App\Models\BookSeries;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * Интеграция: #[ObservedBy] на модели действительно навешивает обсервер,
 * и события created/updated/deleted сбрасывают кеш справочника.
 * Покрытие самих обработчиков — в Tests\Unit\Observers\BookSeriesObserverTest.
 */
class BookSeriesObserverCacheTest extends TestCase
{
    use RefreshDatabase;

    private const string CACHE_KEY = 't_book_series';

    private BookSeriesCacheProvider $cacheProvider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cacheProvider = $this->app->make(BookSeriesCacheProvider::class);
    }

    public function test_creating_series_clears_cache(): void
    {
        $this->warmUpCache();

        BookSeries::factory()->create();

        $this->assertFalse(Cache::has(self::CACHE_KEY));
    }

    public function test_updating_series_clears_cache(): void
    {
        $series = BookSeries::factory()->create();
        $this->warmUpCache();

        $series->update([BookSeries::PARAM_NAME => 'Обновлённая серия']);

        $this->assertFalse(Cache::has(self::CACHE_KEY));
    }

    public function test_deleting_series_clears_cache(): void
    {
        $series = BookSeries::factory()->create();
        $this->warmUpCache();

        $series->delete();

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
