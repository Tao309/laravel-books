<?php

namespace Tests\Feature\CQRS;

use App\CQRS\Bus;
use App\CQRS\Queries\BookSeries\GetBookSeriesQuery;
use App\DTOs\CollectionQueryDto;
use App\Models\BookSeries;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookSeriesBusTest extends TestCase
{
    use RefreshDatabase;

    public function test_query_can_be_handled(): void
    {
        BookSeries::factory()->create();
        $result = $this->app->make(Bus::class)->handle(new GetBookSeriesQuery(new CollectionQueryDto));

        $this->assertNotNull($result);
    }
}
