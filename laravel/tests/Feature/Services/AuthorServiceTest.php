<?php

namespace Tests\Feature\Services;

use App\Services\AuthorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_resolves_from_container(): void
    {
        $this->assertInstanceOf(AuthorService::class, $this->app->make(AuthorService::class));
    }
}
