<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use App\Models\ProductUserData;
use App\Models\User;
use Tests\Feature\Api\Concerns\ActsAsApiClient;
use Tests\TestCase;

/**
 * GET /api/product_user_data/{product_id} — единственный роут ProductUserData.
 * Поведение аналогично book_user_data: скоуп по Auth::id(),
 * firstOrFail → ModelNotFound → HTTP 200 + success:false.
 */
class ProductUserDataApiTest extends TestCase
{
    use ActsAsApiClient;

    private function createForCurrentUser(): ProductUserData
    {
        // Явный shop_id: nested Shop::factory() из ProductFactory получил бы
        // дубликат shops_pkey (ozon id=1 создан в setUp трейта, секвенс не сдвинут)
        return ProductUserData::factory()->create([
            ProductUserData::PARAM_PRODUCT_ID => Product::factory()->create([
                Product::PARAM_SHOP_ID => 1,
            ])->id,
            ProductUserData::PARAM_USER_ID => $this->user->id,
            ProductUserData::PARAM_AVAILABLE => false,
            ProductUserData::PARAM_IS_ARCHIVE => true,
            ProductUserData::PARAM_LISTEN_PRICE_VALUE => 1500,
            ProductUserData::PARAM_COMMENT => 'Проверить комплектацию',
        ]);
    }

    public function test_show_returns_user_data_for_current_user(): void
    {
        $userData = $this->createForCurrentUser();

        $this->apiGet("/api/product_user_data/{$userData->product_id}")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.available', false)
            ->assertJsonPath('data.is_archive', true)
            ->assertJsonPath('data.listen_price_value', 1500)
            ->assertJsonPath('data.comment', 'Проверить комплектацию');
    }

    public function test_show_exposes_foreign_keys(): void
    {
        $userData = $this->createForCurrentUser();

        $this->apiGet("/api/product_user_data/{$userData->product_id}")
            ->assertOk()
            ->assertJsonPath('data.' . ProductUserData::PARAM_PRODUCT_ID, $userData->product_id)
            ->assertJsonPath('data.' . ProductUserData::PARAM_USER_ID, $userData->user_id);
    }

    /**
     * Документация дефекта: ModelNotFoundException → NotFoundHttpException →
     * Throwable-коллбэк → 500 (см. bootstrap/app.php).
     */
    public function test_show_missing_row_is_200_with_success_false(): void
    {
        $this->apiGet('/api/product_user_data/99999')
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }

    /**
     * Документация дефекта: скоуп по Auth::id() → ModelNotFoundException →
     * NotFoundHttpException → Throwable-коллбэк → 500.
     */
    public function test_show_other_users_row_is_200_with_success_false(): void
    {
        $otherUser = User::factory()->create();
        $userData = ProductUserData::factory()->create([
            ProductUserData::PARAM_PRODUCT_ID => Product::factory()->create([
                Product::PARAM_SHOP_ID => 1,
            ])->id,
            ProductUserData::PARAM_USER_ID => $otherUser->id,
        ]);

        $this->apiGet("/api/product_user_data/{$userData->product_id}")
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }
}
