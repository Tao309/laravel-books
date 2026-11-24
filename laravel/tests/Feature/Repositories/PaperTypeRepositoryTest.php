<?php

namespace Tests\Feature\Repositories;

use App\Repositories\PaperTypeRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaperTypeRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_repository_resolves(): void
    {
        $this->assertInstanceOf(PaperTypeRepository::class, $this->app->make(PaperTypeRepository::class));
    }
}
