<?php

namespace Tests\Feature\Observers;

use App\CacheProviders\BindingTypesCacheProvider;
use App\Models\BindingType;
use App\Observers\BindingTypeObserver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class BindingTypeObserverCacheTest extends TestCase
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
        $label = 'U ' . uniqid();

        $model->update([BindingType::PARAM_LABEL => $label]);

        $this->assertSame($label, $this->findCachedLabel($provider, $model->id));
    }

    public function test_observer_invalidates_cache_on_delete(): void
    {
        $model = $this->createModel();
        $provider = $this->makeProvider();
        $before = count($provider->get());

        $model->delete();

        $this->assertSame($before - 1, count($provider->get()));
        $this->assertNull($this->findCachedLabel($provider, $model->id));
    }

    public function test_observer_invalidates_cache_on_restored(): void
    {
        $model = $this->createModel();
        $provider = $this->makeProvider();
        $provider->get();
        Cache::put('t_binding_types', json_encode([]), 3600 * 24 * 30);

        $this->app->make(BindingTypeObserver::class)->restored();

        $this->assertSame($model->label, $this->findCachedLabel($provider, $model->id));
    }

    public function test_observer_invalidates_cache_on_force_deleted(): void
    {
        $model = $this->createModel();
        $provider = $this->makeProvider();
        $provider->get();
        Cache::put('t_binding_types', json_encode([$model->toArray()]), 3600 * 24 * 30);
        $modelId = $model->id;
        $model->deleteQuietly();

        $this->app->make(BindingTypeObserver::class)->forceDeleted();

        $this->assertNull($this->findCachedLabel($provider, $modelId));
    }

    private function makeProvider(): BindingTypesCacheProvider
    {
        Cache::flush();

        return $this->app->make(BindingTypesCacheProvider::class);
    }

    private function createModel(): BindingType
    {
        return BindingType::factory()->create([BindingType::PARAM_LABEL => 'B ' . uniqid()]);
    }

    private function findCachedLabel(BindingTypesCacheProvider $provider, int $id): ?string
    {
        foreach ($provider->get() as $row) {
            if ($row[BindingType::PARAM_ID] === $id) {
                return $row[BindingType::PARAM_LABEL];
            }
        }

        return null;
    }
}
