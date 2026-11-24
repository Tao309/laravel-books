<?php

namespace Tests\Feature\Services;

use App\Services\SameProductsLoader;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SameProductsLoaderTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_resolves(): void
    {
        $this->assertInstanceOf(SameProductsLoader::class, $this->app->make(SameProductsLoader::class));
    }
}
