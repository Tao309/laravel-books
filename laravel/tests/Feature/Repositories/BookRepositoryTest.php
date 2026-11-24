<?php

namespace Tests\Feature\Repositories;

use App\Repositories\BookRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_repository_resolves(): void
    {
        $this->assertInstanceOf(BookRepository::class, $this->app->make(BookRepository::class));
    }
}
