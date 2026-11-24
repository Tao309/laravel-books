<?php

namespace Tests\Feature\Services;

use App\Services\SourceProductTypeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourceProductTypeServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_resolves_from_container(): void
    {
        $this->assertInstanceOf(SourceProductTypeService::class, $this->app->make(SourceProductTypeService::class));
    }
}
