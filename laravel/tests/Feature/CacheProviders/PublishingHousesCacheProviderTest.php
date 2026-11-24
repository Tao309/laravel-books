<?php

namespace Tests\Feature\CacheProviders;

use App\CacheProviders\PublishingHousesCacheProvider;
use App\Models\PublishingHouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PublishingHousesCacheProviderTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_returns_created_house(): void
    {
        Cache::flush();
        $house = PublishingHouse::factory()->create([PublishingHouse::PARAM_NAME => '??? ' . uniqid()]);
        $data = $this->app->make(PublishingHousesCacheProvider::class)->get();
        $this->assertTrue(collect($data)->contains(fn ($i) => ($i['id'] ?? null) === $house->id));
    }
}
