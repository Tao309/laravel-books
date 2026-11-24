<?php

namespace Tests\Feature\Services;

use App\Services\BindingTypeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BindingTypeServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_resolves_from_container(): void
    {
        $this->assertInstanceOf(BindingTypeService::class, $this->app->make(BindingTypeService::class));
    }
}
