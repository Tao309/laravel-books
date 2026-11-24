<?php

namespace Tests\Feature\Services;

use App\Models\Product;
use App\Models\Shop;
use App\Services\MassSaveProductsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MassSaveProductsServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_process_returns_error_for_invalid_item(): void
    {
        $service = $this->app->make(MassSaveProductsService::class);
        $result = $service->process([[
            'id' => 99999,
            'shop_product_id' => 'missing',
            'title' => 'bad',
            'shop' => ['id' => 1],
            'prices' => [['price' => 100, 'date' => '2026.11.01']],
            'stocks' => [['qty' => 1, 'date' => '2026.11.01']],
        ]])->additional;

        $this->assertSame(1, $result['products_count']);
        $this->assertSame(1, $result['errors_count']);
    }

    public function test_process_updates_existing_product(): void
    {
        $shop = Shop::factory()->create();
        $product = Product::factory()->create([Product::PARAM_SHOP_ID => $shop->id, Product::PARAM_TITLE => 'Old']);
        $service = $this->app->make(MassSaveProductsService::class);

        $result = $service->process([[
            'id' => $product->id,
            'shop_product_id' => $product->shop_product_id,
            'title' => 'New',
            'shop' => ['id' => $shop->id],
            'prices' => [['price' => 100, 'date' => '2026.11.01']],
            'stocks' => [['qty' => 1, 'date' => '2026.11.01']],
        ]])->additional;

        $this->assertSame(1, $result['saved_count']);
        $this->assertDatabaseHas(Product::TABLE_NAME, [Product::PARAM_ID => $product->id, Product::PARAM_TITLE => 'New']);
    }
}
