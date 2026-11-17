<?php

namespace Tests\Feature\Services;

use App\Models\Product;
use App\Models\ProductUserData;
use App\Models\Shop;
use App\Models\User;
use App\Services\ArchiveProductService;
use App\Services\SessionShopService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * ArchiveProductService::process: поиск по shop_product_id в магазине
 * сессии (findByShopProduct), обновление is_archive в PUD текущего
 * пользователя, возврат Product (ветка false недостижима — см. characterization).
 */
class ArchiveProductServiceTest extends TestCase
{
    use RefreshDatabase;

    private ArchiveProductService $service;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $shop = Shop::factory()->ozon()->create();
        Shop::factory()->wb()->create();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->app->make(SessionShopService::class)->setShop($shop);

        $this->service = $this->app->make(ArchiveProductService::class);
    }

    private function payload(string $shopProductId, bool $isArchive = true): array
    {
        return [
            Product::PARAM_SHOP_PRODUCT_ID => $shopProductId,
            ProductUserData::PARAM_IS_ARCHIVE => $isArchive,
        ];
    }

    /**
     * Документация дефекта: findByShopProduct() использует firstOrFail(),
     * хотя объявлен `?Product` — отсутствие товара даёт
     * ModelNotFoundException, а не false. Ветка `if (! $product) return false`
     * в ArchiveProductService::process недостижима.
     */
    public function test_process_unknown_shop_product_id_throws_model_not_found(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->service->process($this->payload('unknown12345'));
    }

    /**
     * Документация дефекта: findByShopProduct() скоупится по магазину сессии
     * (ozon, id=1) и падает firstOrFail()-ом на товаре чужого магазина.
     */
    public function test_process_product_of_other_shop_throws_model_not_found(): void
    {
        $foreign = Product::factory()->create([Product::PARAM_SHOP_ID => 2]);

        $this->expectException(ModelNotFoundException::class);

        $this->service->process($this->payload($foreign->shop_product_id));
    }

    public function test_process_marks_pud_archived_for_current_user(): void
    {
        $product = Product::factory()->create([Product::PARAM_SHOP_ID => 1]);

        ProductUserData::factory()->create([
            ProductUserData::PARAM_PRODUCT_ID => $product->id,
            ProductUserData::PARAM_USER_ID => $this->user->id,
        ]);

        $result = $this->service->process($this->payload($product->shop_product_id));

        $this->assertInstanceOf(Product::class, $result);
        $this->assertTrue($result->relationLoaded('productUserData'));
        $this->assertTrue($result->productUserData->{ProductUserData::PARAM_IS_ARCHIVE});

        $this->assertDatabaseHas(ProductUserData::TABLE_NAME, [
            ProductUserData::PARAM_PRODUCT_ID => $product->id,
            ProductUserData::PARAM_USER_ID => $this->user->id,
            ProductUserData::PARAM_IS_ARCHIVE => true,
        ]);
    }

    public function test_process_leaves_other_users_pud_untouched(): void
    {
        $otherUser = User::factory()->create();

        $product = Product::factory()->create([Product::PARAM_SHOP_ID => 1]);

        ProductUserData::factory()->create([
            ProductUserData::PARAM_PRODUCT_ID => $product->id,
            ProductUserData::PARAM_USER_ID => $otherUser->id,
        ]);

        $result = $this->service->process($this->payload($product->shop_product_id));

        $this->assertInstanceOf(Product::class, $result);
        $this->assertNull($result->productUserData);

        $this->assertDatabaseHas(ProductUserData::TABLE_NAME, [
            ProductUserData::PARAM_PRODUCT_ID => $product->id,
            ProductUserData::PARAM_USER_ID => $otherUser->id,
            ProductUserData::PARAM_IS_ARCHIVE => false,
        ]);
    }
}
