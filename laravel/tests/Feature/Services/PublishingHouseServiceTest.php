<?php

namespace Tests\Feature\Services;

use App\Services\PublishingHouseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublishingHouseServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_resolves_from_container(): void
    {
        $this->assertInstanceOf(PublishingHouseService::class, $this->app->make(PublishingHouseService::class));
    }
}
