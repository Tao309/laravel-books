<?php

namespace Tests\Feature\CQRS;

use App\CQRS\Bus;
use App\CQRS\Commands\Products\DestroyProductCommand;
use App\CQRS\Queries\Cities\GetCitiesQuery;
use App\DTOs\CollectionQueryDto;
use App\Models\City;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusTest extends TestCase
{
    use RefreshDatabase;

    public function test_query_returns_handler_result_from_real_container(): void
    {
        $city = City::factory()->create();

        $result = $this->app->make(Bus::class)->handle(new GetCitiesQuery(new CollectionQueryDto));

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertTrue($result->contains(fn ($item) => $item->is($city)));
    }

    public function test_command_deletes_product_through_repository(): void
    {
        $product = Product::factory()->create();

        $this->assertTrue(
            $this->app->make(Bus::class)->handle(new DestroyProductCommand($product->id))
        );

        $this->assertDatabaseMissing(Product::TABLE_NAME, [Product::PARAM_ID => $product->id]);
    }

    public function test_handler_exceptions_are_not_swallowed(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->app->make(Bus::class)->handle(new DestroyProductCommand(999));
    }

    public function test_bus_is_registered_as_singleton(): void
    {
        $this->assertSame(
            $this->app->make(Bus::class),
            $this->app->make(Bus::class),
        );
    }
}
