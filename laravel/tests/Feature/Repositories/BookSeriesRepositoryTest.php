<?php

namespace Tests\Feature\Repositories;

use App\Repositories\BookSeriesRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookSeriesRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_repository_resolves(): void
    {
        $this->assertInstanceOf(BookSeriesRepository::class, $this->app->make(BookSeriesRepository::class));
    }
}
