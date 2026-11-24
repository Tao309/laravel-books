<?php

namespace Tests\Feature\CacheProviders;

use App\CacheProviders\PublishingBrandsCacheProvider;
use App\Models\PublishingBrand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PublishingBrandsCacheProviderTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_returns_created_brand(): void
    {
        Cache::flush();
        $brand = PublishingBrand::factory()->create([PublishingBrand::PARAM_NAME => '????? ' . uniqid()]);
        $data = $this->app->make(PublishingBrandsCacheProvider::class)->get();
        $this->assertTrue(collect($data)->contains(fn ($i) => ($i['id'] ?? null) === $brand->id));
    }
}
