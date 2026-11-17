<?php

namespace Tests\Feature\CacheProviders;

use App\CacheProviders\BookSeriesCacheProvider;
use App\Models\BookSeries;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * В phpunit.xml CACHE_STORE=array — кеш изолирован на каждый тест.
 * Имя ключа — защищённая константа провайдера, в тесте дублируется литералом.
 */
class BookSeriesCacheProviderTest extends TestCase
{
    use RefreshDatabase;

    private const string CACHE_KEY = 't_book_series';

    public function test_get_returns_series_from_db(): void
    {
        $series = BookSeries::factory()->create();

        $data = $this->app->make(BookSeriesCacheProvider::class)->get();

        $this->assertSame(
            [['id' => $series->id, 'name' => $series->name]],
            $data,
        );
    }

    public function test_get_caches_data_under_provider_key(): void
    {
        BookSeries::factory()->create();

        $this->app->make(BookSeriesCacheProvider::class)->get();

        $this->assertTrue(Cache::has(self::CACHE_KEY));
    }

    public function test_get_reuses_cached_payload(): void
    {
        $provider = $this->app->make(BookSeriesCacheProvider::class);
        $provider->get();

        // новая строка не попадёт в выдачу, пока кеш жив
        BookSeries::factory()->create();

        $this->assertCount(1, $provider->get());
    }

    public function test_delete_clears_cache(): void
    {
        BookSeries::factory()->create();
        $provider = $this->app->make(BookSeriesCacheProvider::class);

        $provider->get();
        $provider->delete();

        $this->assertFalse(Cache::has(self::CACHE_KEY));
    }
}
