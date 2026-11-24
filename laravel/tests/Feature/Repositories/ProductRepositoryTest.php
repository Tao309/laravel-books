<?php

namespace Tests\Feature\Repositories;

use App\Repositories\ProductRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_repository_resolves(): void
    {
        $this->assertInstanceOf(ProductRepository::class, $this->app->make(ProductRepository::class));
    }
}
