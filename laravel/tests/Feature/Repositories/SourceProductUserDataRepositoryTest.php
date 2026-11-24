<?php

namespace Tests\Feature\Repositories;

use App\Repositories\SourceProductUserDataRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourceProductUserDataRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_repository_resolves(): void
    {
        $this->assertInstanceOf(SourceProductUserDataRepository::class, $this->app->make(SourceProductUserDataRepository::class));
    }
}
