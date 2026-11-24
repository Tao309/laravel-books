<?php

namespace Tests\Feature\Repositories;

use App\Repositories\BindingTypeRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BindingTypeRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_repository_resolves(): void
    {
        $this->assertInstanceOf(BindingTypeRepository::class, $this->app->make(BindingTypeRepository::class));
    }
}
