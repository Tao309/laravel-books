<?php

namespace Tests\Feature\CQRS;

use App\CQRS\Bus;
use App\CQRS\Queries\PaperTypes\GetPaperTypesQuery;
use App\DTOs\CollectionQueryDto;
use App\Models\PaperType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaperTypesBusTest extends TestCase
{
    use RefreshDatabase;

    public function test_query_can_be_handled(): void
    {
        PaperType::factory()->create();
        $result = $this->app->make(Bus::class)->handle(new GetPaperTypesQuery(new CollectionQueryDto));

        $this->assertNotNull($result);
    }
}
