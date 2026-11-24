<?php

namespace Tests\Feature\Services;

use App\Services\CityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CityServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_resolves_from_container(): void
    {
        $this->assertInstanceOf(CityService::class, $this->app->make(CityService::class));
    }
}
