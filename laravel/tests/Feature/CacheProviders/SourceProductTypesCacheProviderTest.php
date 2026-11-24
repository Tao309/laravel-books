<?php

namespace Tests\Feature\CacheProviders;

use App\CacheProviders\SourceProductsTypesCacheProvider;
use App\Models\SourceProductType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SourceProductTypesCacheProviderTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_returns_created_type(): void
    {
        Cache::flush();
        $type = SourceProductType::factory()->create([
            SourceProductType::PARAM_NAME => '??? ' . uniqid(),
            SourceProductType::PARAM_CODE => 'code-' . substr(uniqid(), -8),
        ]);

        $data = $this->app->make(SourceProductsTypesCacheProvider::class)->get();

        $this->assertTrue(collect($data)->contains(fn ($i) => ($i['id'] ?? null) === $type->id));
    }
}
