<?php

namespace Tests\Feature\CQRS;

use App\CQRS\Bus;
use App\CQRS\Queries\Authors\GetAuthorsPaginateQuery;
use App\DTOs\CollectionQueryDto;
use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorsBusTest extends TestCase
{
    use RefreshDatabase;

    public function test_query_can_be_handled(): void
    {
        Author::factory()->create();
        $result = $this->app->make(Bus::class)->handle(new GetAuthorsPaginateQuery(new CollectionQueryDto));

        $this->assertNotNull($result);
    }
}
