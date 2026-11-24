<?php

namespace Tests\Feature\Observers;

use App\CacheProviders\PublishingBrandsCacheProvider;
use App\Models\PublishingBrand;
use App\Observers\PublishingBrandObserver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PublishingBrandObserverCacheTest extends TestCase
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

        $model->update([PublishingBrand::PARAM_NAME => $name]);

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
        Cache::put('t_publishing_brands', json_encode([]), 3600 * 24 * 30);

        $this->app->make(PublishingBrandObserver::class)->restored();

        $this->assertSame($model->name, $this->findCachedName($provider, $model->id));
    }

    public function test_observer_invalidates_cache_on_force_deleted(): void
    {
        $model = $this->createModel();
        $provider = $this->makeProvider();
        $provider->get();
        Cache::put('t_publishing_brands', json_encode([$model->toArray()]), 3600 * 24 * 30);
        $modelId = $model->id;
        $model->deleteQuietly();

        $this->app->make(PublishingBrandObserver::class)->forceDeleted();

        $this->assertNull($this->findCachedName($provider, $modelId));
    }

    private function makeProvider(): PublishingBrandsCacheProvider
    {
        Cache::flush();

        return $this->app->make(PublishingBrandsCacheProvider::class);
    }

    private function createModel(): PublishingBrand
    {
        return PublishingBrand::factory()->create([PublishingBrand::PARAM_NAME => 'B ' . uniqid()]);
    }

    private function findCachedName(PublishingBrandsCacheProvider $provider, int $id): ?string
    {
        foreach ($provider->get() as $row) {
            if ($row[PublishingBrand::PARAM_ID] === $id) {
                return $row[PublishingBrand::PARAM_NAME];
            }
        }

        return null;
    }
}
