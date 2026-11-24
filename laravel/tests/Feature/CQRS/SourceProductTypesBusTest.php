<?php

namespace Tests\Feature\CQRS;

use App\CQRS\Bus;
use App\CQRS\Queries\SourceProductTypes\GetSourceProductTypesQuery;
use App\DTOs\CollectionQueryDto;
use App\Models\SourceProductType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourceProductTypesBusTest extends TestCase
{
    use RefreshDatabase;

    public function test_query_can_be_handled(): void
    {
        SourceProductType::factory()->create();
        $result = $this->app->make(Bus::class)->handle(new GetSourceProductTypesQuery(new CollectionQueryDto));

        $this->assertNotNull($result);
    }
}
