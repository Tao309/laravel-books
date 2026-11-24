<?php

namespace Tests\Feature\Observers;

use App\CacheProviders\BookSeriesCacheProvider;
use App\Models\BookSeries;
use App\Observers\BookSeriesObserver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class BookSeriesObserverCacheTest extends TestCase
{
    use RefreshDatabase;

    public function test_observer_invalidates_cache_on_create(): void
    {
        $provider = $this->makeProvider();
        $before = count($provider->get());

        $this->createModel();

        $this->assertGreaterThanOrEqual($before + 1, count($provider->get()));
    }

    public function test_observer_invalidates_cache_on_update(): void
    {
        $model = $this->createModel();
        $provider = $this->makeProvider();
        $provider->get();
        $name = 'U ' . uniqid();

        $model->update([BookSeries::PARAM_NAME => $name]);

        $this->assertSame($name, $this->findCachedName($provider, $model->id));
    }

    public function test_observer_invalidates_cache_on_delete(): void
    {
        $model = $this->createModel();
        $provider = $this->makeProvider();
        $before = count($provider->get());

        $model->delete();

        $this->assertSame($before - 1, count($provider->get()));
        $this->assertNull($this->findCachedName($provider, $model->id));
    }

    public function test_observer_invalidates_cache_on_restored(): void
    {
        $model = $this->createModel();
        $provider = $this->makeProvider();
        $provider->get();
        Cache::put('t_book_series', json_encode([]), 3600 * 24 * 30);

        $this->app->make(BookSeriesObserver::class)->restored();

        $this->assertSame($model->name, $this->findCachedName($provider, $model->id));
    }

    public function test_observer_invalidates_cache_on_force_deleted(): void
    {
        $model = $this->createModel();
        $provider = $this->makeProvider();
        $provider->get();
        Cache::put('t_book_series', json_encode([$model->toArray()]), 3600 * 24 * 30);
        $modelId = $model->id;
        $model->deleteQuietly();

        $this->app->make(BookSeriesObserver::class)->forceDeleted();

        $this->assertNull($this->findCachedName($provider, $modelId));
    }

    private function makeProvider(): BookSeriesCacheProvider
    {
        Cache::flush();

        return $this->app->make(BookSeriesCacheProvider::class);
    }

    private function createModel(): BookSeries
    {
        return BookSeries::factory()->create([BookSeries::PARAM_NAME => 'S ' . uniqid()]);
    }

    private function findCachedName(BookSeriesCacheProvider $provider, int $id): ?string
    {
        foreach ($provider->get() as $row) {
            if ($row[BookSeries::PARAM_ID] === $id) {
                return $row[BookSeries::PARAM_NAME];
            }
        }

        return null;
    }
}
