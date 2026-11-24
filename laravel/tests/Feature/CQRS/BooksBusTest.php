<?php

namespace Tests\Feature\CQRS;

use App\CQRS\Bus;
use App\CQRS\Queries\Books\GetBooksPaginateQuery;
use App\DTOs\CollectionQueryDto;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksBusTest extends TestCase
{
    use RefreshDatabase;

    public function test_query_can_be_handled(): void
    {
        Book::factory()->create();
        $result = $this->app->make(Bus::class)->handle(new GetBooksPaginateQuery(new CollectionQueryDto));

        $this->assertNotNull($result);
    }
}
