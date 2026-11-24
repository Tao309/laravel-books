<?php

namespace Tests\Feature\Services;

use App\Services\LinkToProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinkToProductServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_resolves(): void
    {
        $this->assertInstanceOf(LinkToProductService::class, $this->app->make(LinkToProductService::class));
    }
}
