<?php

namespace Tests\Feature\Repositories;

use App\Repositories\SourceProductRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourceProductRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_repository_resolves(): void
    {
        $this->assertInstanceOf(SourceProductRepository::class, $this->app->make(SourceProductRepository::class));
    }
}
