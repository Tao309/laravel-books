<?php

namespace Tests\Feature\Services;

use App\Models\Book;
use App\Models\Product;
use App\Models\Shop;
use App\Services\SessionShopService;
use App\Services\UnlinkFromProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnlinkFromProductServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_process_unlinks_source(): void
    {
        $shop = Shop::factory()->create();
        $book = Book::factory()->create();
        $product = Product::factory()->create([
            Product::PARAM_SHOP_ID => $shop->id,
            Product::PARAM_SOURCE_TYPE => Book::class,
            Product::PARAM_SOURCE_ID => $book->id,
        ]);

        $sessionShopService = $this->app->make(SessionShopService::class);
        $sessionShopService->setShop($shop);
        $this->app->instance(SessionShopService::class, $sessionShopService);

        $result = $this->app->make(UnlinkFromProductService::class)->process($product->id);

        $this->assertNull($result->source_type);
        $this->assertNull($result->source_id);
        $this->assertDatabaseHas(Product::TABLE_NAME, [
            Product::PARAM_ID => $product->id,
            Product::PARAM_SOURCE_TYPE => null,
            Product::PARAM_SOURCE_ID => null,
        ]);
    }
}
