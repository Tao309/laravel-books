<?php

namespace Tests\Feature\Services;

use App\Exceptions\ApiException;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use App\Services\SessionShopService;
use App\Services\UnlinkFromProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * UnlinkFromProductService::process: отвязка source (Book/SourceProduct)
 * от товара. Товар ищется через productRepo->get() — со shop-скоупом
 * сессии и прогоном SameProductsLoader.
 */
class UnlinkFromProductServiceTest extends TestCase
{
    use RefreshDatabase;

    private UnlinkFromProductService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $shop = Shop::factory()->ozon()->create();

        $this->actingAs(User::factory()->create());

        $this->app->make(SessionShopService::class)->setShop($shop);

        $this->service = $this->app->make(UnlinkFromProductService::class);
    }

    public function test_process_clears_source_and_persists(): void
    {
        $product = Product::factory()->forBook()->create([Product::PARAM_SHOP_ID => 1]);

        $result = $this->service->process($product->id);

        $this->assertSame($product->id, $result->id);
        $this->assertNull($result->source);

        $this->assertDatabaseHas(Product::TABLE_NAME, [
            Product::PARAM_ID => $product->id,
            Product::PARAM_SOURCE_TYPE => null,
            Product::PARAM_SOURCE_ID => null,
        ]);
    }

    public function test_process_without_source_throws_api_exception(): void
    {
        $product = Product::factory()->state(fn () => [
            Product::PARAM_SOURCE_TYPE => null,
            Product::PARAM_SOURCE_ID => null,
        ])->create([Product::PARAM_SHOP_ID => 1]);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Product already no has Book or SourceProduct');

        $this->service->process($product->id);
    }

    public function test_process_returns_product(): void
    {
        $product = Product::factory()->forBook()->create([Product::PARAM_SHOP_ID => 1]);

        $result = $this->service->process($product->id);

        $this->assertInstanceOf(Product::class, $result);
        $this->assertTrue($result->relationLoaded('productUserData'));
    }
}
