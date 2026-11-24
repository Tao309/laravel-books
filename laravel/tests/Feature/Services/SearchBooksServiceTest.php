<?php

namespace Tests\Feature\Services;

use App\Services\SearchBooksService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchBooksServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_resolves(): void
    {
        $this->assertInstanceOf(SearchBooksService::class, $this->app->make(SearchBooksService::class));
    }
}
