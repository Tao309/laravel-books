<?php

namespace Tests\Feature\Observers;

use App\CacheProviders\PublishingHousesCacheProvider;
use App\Models\PublishingHouse;
use App\Observers\PublishingHouseObserver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PublishingHouseObserverCacheTest extends TestCase
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

        $model->update([PublishingHouse::PARAM_NAME => $name]);

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
        Cache::put('t_publishing_houses', json_encode([]), 3600 * 24 * 30);

        $this->app->make(PublishingHouseObserver::class)->restored();

        $this->assertSame($model->name, $this->findCachedName($provider, $model->id));
    }

    public function test_observer_invalidates_cache_on_force_deleted(): void
    {
        $model = $this->createModel();
        $provider = $this->makeProvider();
        $provider->get();
        Cache::put('t_publishing_houses', json_encode([$model->toArray()]), 3600 * 24 * 30);
        $modelId = $model->id;
        $model->deleteQuietly();

        $this->app->make(PublishingHouseObserver::class)->forceDeleted();

        $this->assertNull($this->findCachedName($provider, $modelId));
    }

    private function makeProvider(): PublishingHousesCacheProvider
    {
        Cache::flush();

        return $this->app->make(PublishingHousesCacheProvider::class);
    }

    private function createModel(): PublishingHouse
    {
        return PublishingHouse::factory()->create([PublishingHouse::PARAM_NAME => 'H ' . uniqid()]);
    }

    private function findCachedName(PublishingHousesCacheProvider $provider, int $id): ?string
    {
        foreach ($provider->get() as $row) {
            if ($row[PublishingHouse::PARAM_ID] === $id) {
                return $row[PublishingHouse::PARAM_NAME];
            }
        }

        return null;
    }
}
