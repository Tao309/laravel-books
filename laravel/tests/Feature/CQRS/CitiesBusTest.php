<?php

namespace Tests\Feature\CQRS;

use App\CQRS\Bus;
use App\CQRS\Queries\Cities\GetCitiesQuery;
use App\DTOs\CollectionQueryDto;
use App\Models\City;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CitiesBusTest extends TestCase
{
    use RefreshDatabase;

    public function test_query_can_be_handled(): void
    {
        City::factory()->create();
        $result = $this->app->make(Bus::class)->handle(new GetCitiesQuery(new CollectionQueryDto));

        $this->assertNotNull($result);
    }
}
