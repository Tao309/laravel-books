<?php

namespace Tests\Feature\Services;

use App\Services\SearchSourceProductsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchSourceProductsServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_resolves(): void
    {
        $this->assertInstanceOf(SearchSourceProductsService::class, $this->app->make(SearchSourceProductsService::class));
    }
}
