<?php

namespace Tests\Feature\CQRS;

use App\CQRS\Bus;
use App\CQRS\Queries\BindingTypes\GetBindingTypesQuery;
use App\DTOs\CollectionQueryDto;
use App\Models\BindingType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BindingTypesBusTest extends TestCase
{
    use RefreshDatabase;

    public function test_query_can_be_handled(): void
    {
        BindingType::factory()->create();
        $result = $this->app->make(Bus::class)->handle(new GetBindingTypesQuery(new CollectionQueryDto));

        $this->assertNotNull($result);
    }
}
