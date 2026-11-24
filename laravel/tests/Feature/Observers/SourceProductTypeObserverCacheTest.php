<?php

namespace Tests\Feature\Observers;

use App\CacheProviders\SourceProductsTypesCacheProvider;
use App\Models\SourceProductType;
use App\Observers\SourceProductTypeObserver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SourceProductTypeObserverCacheTest extends TestCase
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

        $model->update([SourceProductType::PARAM_NAME => $name]);

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
        Cache::put('t_source_product_types', json_encode([]), 3600 * 24 * 30);

        $this->app->make(SourceProductTypeObserver::class)->restored();

        $this->assertSame($model->name, $this->findCachedName($provider, $model->id));
    }

    public function test_observer_invalidates_cache_on_force_deleted(): void
    {
        $model = $this->createModel();
        $provider = $this->makeProvider();
        $provider->get();
        Cache::put('t_source_product_types', json_encode([$model->toArray()]), 3600 * 24 * 30);
        $modelId = $model->id;
        $model->deleteQuietly();

        $this->app->make(SourceProductTypeObserver::class)->forceDeleted();

        $this->assertNull($this->findCachedName($provider, $modelId));
    }

    private function makeProvider(): SourceProductsTypesCacheProvider
    {
        Cache::flush();

        return $this->app->make(SourceProductsTypesCacheProvider::class);
    }

    private function createModel(): SourceProductType
    {
        return SourceProductType::factory()->create([
            SourceProductType::PARAM_NAME => 'T ' . uniqid(),
            SourceProductType::PARAM_CODE => 'c' . substr(uniqid(), -8),
        ]);
    }

    private function findCachedName(SourceProductsTypesCacheProvider $provider, int $id): ?string
    {
        foreach ($provider->get() as $row) {
            if ($row[SourceProductType::PARAM_ID] === $id) {
                return $row[SourceProductType::PARAM_NAME];
            }
        }

        return null;
    }
}
