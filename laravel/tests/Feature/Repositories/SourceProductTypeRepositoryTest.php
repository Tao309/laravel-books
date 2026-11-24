<?php

namespace Tests\Feature\Repositories;

use App\Repositories\SourceProductTypeRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourceProductTypeRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_repository_resolves(): void
    {
        $this->assertInstanceOf(SourceProductTypeRepository::class, $this->app->make(SourceProductTypeRepository::class));
    }
}
