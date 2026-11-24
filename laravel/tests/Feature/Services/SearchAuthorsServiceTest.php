<?php

namespace Tests\Feature\Services;

use App\Services\SearchAuthorsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchAuthorsServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_resolves(): void
    {
        $this->assertInstanceOf(SearchAuthorsService::class, $this->app->make(SearchAuthorsService::class));
    }
}
