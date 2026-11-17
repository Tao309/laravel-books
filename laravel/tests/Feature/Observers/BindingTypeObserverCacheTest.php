<?php

namespace Tests\Feature\Observers;

use App\CacheProviders\BindingTypesCacheProvider;
use App\Models\BindingType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * Интеграция: #[ObservedBy] на модели действительно навешивает обсервер,
 * и события created/updated/deleted сбрасывают кеш справочника.
 * Покрытие самих обработчиков — в Tests\Unit\Observers\BindingTypeObserverTest.
 */
class BindingTypeObserverCacheTest extends TestCase
{
    use RefreshDatabase;

    private const string CACHE_KEY = 't_binding_types';

    private BindingTypesCacheProvider $cacheProvider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cacheProvider = $this->app->make(BindingTypesCacheProvider::class);
    }

    public function test_creating_binding_type_clears_cache(): void
    {
        $this->warmUpCache();

        BindingType::factory()->create();

        $this->assertFalse(Cache::has(self::CACHE_KEY));
    }

    public function test_updating_binding_type_clears_cache(): void
    {
        $bindingType = BindingType::factory()->create();
        $this->warmUpCache();

        $bindingType->update([BindingType::PARAM_LABEL => 'Обновлённый переплёт']);

        $this->assertFalse(Cache::has(self::CACHE_KEY));
    }

    public function test_deleting_binding_type_clears_cache(): void
    {
        $bindingType = BindingType::factory()->create();
        $this->warmUpCache();

        $bindingType->delete();

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
