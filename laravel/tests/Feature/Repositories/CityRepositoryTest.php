<?php

namespace Tests\Feature\Repositories;

use App\Repositories\CityRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CityRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_repository_resolves(): void
    {
        $this->assertInstanceOf(CityRepository::class, $this->app->make(CityRepository::class));
    }
}
