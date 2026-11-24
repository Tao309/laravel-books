<?php

namespace Tests\Feature\Repositories;

use App\Repositories\ShopRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_repository_resolves(): void
    {
        $this->assertInstanceOf(ShopRepository::class, $this->app->make(ShopRepository::class));
    }
}
