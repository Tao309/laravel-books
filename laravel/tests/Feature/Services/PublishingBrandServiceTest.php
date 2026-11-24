<?php

namespace Tests\Feature\Services;

use App\Services\PublishingBrandService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublishingBrandServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_resolves_from_container(): void
    {
        $this->assertInstanceOf(PublishingBrandService::class, $this->app->make(PublishingBrandService::class));
    }
}
