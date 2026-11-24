<?php

namespace Tests\Feature\Repositories;

use App\Repositories\PublishingBrandRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublishingBrandRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_repository_resolves(): void
    {
        $this->assertInstanceOf(PublishingBrandRepository::class, $this->app->make(PublishingBrandRepository::class));
    }
}
