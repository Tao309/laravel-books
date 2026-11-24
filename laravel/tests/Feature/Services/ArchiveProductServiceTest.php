<?php

namespace Tests\Feature\Services;

use App\Models\Product;
use App\Models\ProductUserData;
use App\Models\Shop;
use App\Models\User;
use App\Services\ArchiveProductService;
use App\Services\SessionShopService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArchiveProductServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_process_archives_current_user_data_and_returns_loaded_product(): void
    {
        $shop = Shop::factory()->ozon()->create();
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->useShop($shop);

        $product = Product::factory()->create([
            Product::PARAM_SHOP_ID => $shop->id,
            Product::PARAM_SOURCE_TYPE => null,
            Product::PARAM_SOURCE_ID => null,
            Product::PARAM_SHOP_PRODUCT_ID => 'archive-000001',
        ]);

        ProductUserData::factory()->create([
            ProductUserData::PARAM_PRODUCT_ID => $product->id,
            ProductUserData::PARAM_USER_ID => $user->id,
            ProductUserData::PARAM_IS_ARCHIVE => false,
        ]);

        $result = $this->app->make(ArchiveProductService::class)->process([
            Product::PARAM_SHOP_PRODUCT_ID => $product->shop_product_id,
            ProductUserData::PARAM_IS_ARCHIVE => true,
        ]);

        $this->assertInstanceOf(Product::class, $result);
        $this->assertTrue($result->relationLoaded('productUserData'));
        $this->assertTrue((bool) $result->productUserData->{ProductUserData::PARAM_IS_ARCHIVE});
        $this->assertDatabaseHas(ProductUserData::TABLE_NAME, [
            ProductUserData::PARAM_PRODUCT_ID => $product->id,
            ProductUserData::PARAM_USER_ID => $user->id,
            ProductUserData::PARAM_IS_ARCHIVE => true,
        ]);
    }

    public function test_process_returns_false_when_product_is_not_in_session_shop(): void
    {
        $shop = Shop::factory()->ozon()->create();
        $this->useShop($shop);

        $result = $this->app->make(ArchiveProductService::class)->process([
            Product::PARAM_SHOP_PRODUCT_ID => 'missing-000001',
            ProductUserData::PARAM_IS_ARCHIVE => true,
        ]);

        $this->assertFalse($result);
    }

    private function useShop(Shop $shop): void
    {
        $service = $this->app->make(SessionShopService::class);
        $service->setShop($shop);
        $this->app->instance(SessionShopService::class, $service);
    }
}
