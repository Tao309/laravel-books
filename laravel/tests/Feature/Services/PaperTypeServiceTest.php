<?php

namespace Tests\Feature\Services;

use App\Services\PaperTypeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaperTypeServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_resolves_from_container(): void
    {
        $this->assertInstanceOf(PaperTypeService::class, $this->app->make(PaperTypeService::class));
    }
}
