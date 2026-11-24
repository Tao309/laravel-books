<?php

namespace Tests\Feature\CQRS;

use App\CQRS\Bus;
use App\CQRS\Queries\Shops\GetShopsQuery;
use App\DTOs\CollectionQueryDto;
use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopsBusTest extends TestCase
{
    use RefreshDatabase;

    public function test_query_can_be_handled(): void
    {
        Shop::factory()->create();
        $result = $this->app->make(Bus::class)->handle(new GetShopsQuery(new CollectionQueryDto));

        $this->assertNotNull($result);
    }
}
