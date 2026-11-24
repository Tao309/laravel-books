<?php

namespace Tests\Feature\CacheProviders;

use App\CacheProviders\BindingTypesCacheProvider;
use App\Models\BindingType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class BindingTypesCacheProviderTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_returns_created_binding_type(): void
    {
        Cache::flush();
        $bt = BindingType::factory()->create([BindingType::PARAM_LABEL => '???? ' . uniqid()]);

        $data = $this->app->make(BindingTypesCacheProvider::class)->get();

        $this->assertContains(['id' => $bt->id, 'label' => $bt->label], $data);
    }

    public function test_delete_clears_cache(): void
    {
        Cache::flush();
        $provider = $this->app->make(BindingTypesCacheProvider::class);
        $initial = $provider->get();

        BindingType::factory()->create([BindingType::PARAM_LABEL => '????? ' . uniqid()]);
        $provider->delete();

        $this->assertCount(count($initial) + 1, $provider->get());
    }
}
