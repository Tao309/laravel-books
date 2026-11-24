<?php

namespace Tests\Feature\Services;

use App\Services\ShopService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_resolves_from_container(): void
    {
        $this->assertInstanceOf(ShopService::class, $this->app->make(ShopService::class));
    }
}
