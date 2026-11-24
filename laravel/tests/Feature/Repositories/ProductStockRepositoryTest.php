<?php

namespace Tests\Feature\Repositories;

use App\Repositories\ProductStockRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductStockRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_repository_resolves(): void
    {
        $this->assertInstanceOf(ProductStockRepository::class, $this->app->make(ProductStockRepository::class));
    }
}
