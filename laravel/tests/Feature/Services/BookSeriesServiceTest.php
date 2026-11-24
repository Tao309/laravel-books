<?php

namespace Tests\Feature\Services;

use App\Services\BookSeriesService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookSeriesServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_resolves_from_container(): void
    {
        $this->assertInstanceOf(BookSeriesService::class, $this->app->make(BookSeriesService::class));
    }
}
