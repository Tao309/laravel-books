<?php

namespace Tests\Feature\Observers;

use App\CacheProviders\PaperTypesCacheProvider;
use App\Models\PaperType;
use App\Observers\PaperTypeObserver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PaperTypeObserverCacheTest extends TestCase
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

        $model->update([PaperType::PARAM_LABEL => $label]);

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
        Cache::put('t_paper_types', json_encode([]), 3600 * 24 * 30);

        $this->app->make(PaperTypeObserver::class)->restored();

        $this->assertSame($model->label, $this->findCachedLabel($provider, $model->id));
    }

    public function test_observer_invalidates_cache_on_force_deleted(): void
    {
        $model = $this->createModel();
        $provider = $this->makeProvider();
        $provider->get();
        Cache::put('t_paper_types', json_encode([$model->toArray()]), 3600 * 24 * 30);
        $modelId = $model->id;
        $model->deleteQuietly();

        $this->app->make(PaperTypeObserver::class)->forceDeleted();

        $this->assertNull($this->findCachedLabel($provider, $modelId));
    }

    private function makeProvider(): PaperTypesCacheProvider
    {
        Cache::flush();

        return $this->app->make(PaperTypesCacheProvider::class);
    }

    private function createModel(): PaperType
    {
        return PaperType::factory()->create([PaperType::PARAM_LABEL => 'P ' . uniqid()]);
    }

    private function findCachedLabel(PaperTypesCacheProvider $provider, int $id): ?string
    {
        foreach ($provider->get() as $row) {
            if ($row[PaperType::PARAM_ID] === $id) {
                return $row[PaperType::PARAM_LABEL];
            }
        }

        return null;
    }
}
