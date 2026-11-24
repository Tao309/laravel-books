<?php

namespace Tests\Feature\Repositories;

use App\Repositories\ProductUserDataRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductUserDataRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_repository_resolves(): void
    {
        $this->assertInstanceOf(ProductUserDataRepository::class, $this->app->make(ProductUserDataRepository::class));
    }
}
