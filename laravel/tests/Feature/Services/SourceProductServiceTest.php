<?php

namespace Tests\Feature\Services;

use App\Services\SourceProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourceProductServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_resolves_from_container(): void
    {
        $this->assertInstanceOf(SourceProductService::class, $this->app->make(SourceProductService::class));
    }
}
