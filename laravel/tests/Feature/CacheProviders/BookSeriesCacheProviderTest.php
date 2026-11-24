<?php

namespace Tests\Feature\CacheProviders;

use App\CacheProviders\BookSeriesCacheProvider;
use App\Models\BookSeries;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class BookSeriesCacheProviderTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_returns_created_series(): void
    {
        Cache::flush();
        $series = BookSeries::factory()->create([BookSeries::PARAM_NAME => '????? ' . uniqid()]);
        $data = $this->app->make(BookSeriesCacheProvider::class)->get();
        $this->assertTrue(collect($data)->contains(fn ($i) => ($i['id'] ?? null) === $series->id));
    }
}
