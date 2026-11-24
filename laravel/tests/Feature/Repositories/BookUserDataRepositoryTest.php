<?php

namespace Tests\Feature\Repositories;

use App\Repositories\BookUserDataRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookUserDataRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_repository_resolves(): void
    {
        $this->assertInstanceOf(BookUserDataRepository::class, $this->app->make(BookUserDataRepository::class));
    }
}
