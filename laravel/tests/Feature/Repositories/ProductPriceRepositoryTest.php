<?php

namespace Tests\Feature\Repositories;

use App\Repositories\ProductPriceRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductPriceRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_repository_resolves(): void
    {
        $this->assertInstanceOf(ProductPriceRepository::class, $this->app->make(ProductPriceRepository::class));
    }
}
