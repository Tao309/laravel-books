<?php

namespace Tests\Feature\Repositories;

use App\Repositories\PublishingHouseRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublishingHouseRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_repository_resolves(): void
    {
        $this->assertInstanceOf(PublishingHouseRepository::class, $this->app->make(PublishingHouseRepository::class));
    }
}
