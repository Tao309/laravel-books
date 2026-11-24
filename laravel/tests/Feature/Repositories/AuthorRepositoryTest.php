<?php

namespace Tests\Feature\Repositories;

use App\Repositories\AuthorRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_repository_resolves(): void
    {
        $this->assertInstanceOf(AuthorRepository::class, $this->app->make(AuthorRepository::class));
    }
}
