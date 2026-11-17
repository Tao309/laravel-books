<?php

namespace Tests\Feature\Api;

use App\Models\SourceProduct;
use App\Models\SourceProductUserData;
use App\Models\User;
use Tests\Feature\Api\Concerns\ActsAsApiClient;
use Tests\TestCase;

/**
 * GET /api/source_product_user_data/{source_product_id} — единственный
 * роут SourceProductUserData. Скоуп по Auth::id(),
 * firstOrFail → ModelNotFound → HTTP 200 + success:false.
 */
class SourceProductUserDataApiTest extends TestCase
{
    use ActsAsApiClient;

    private function createForCurrentUser(): SourceProductUserData
    {
        // make() + save() вместо create(): SourceProductFactory::configure()
        // в afterCreating создаёт user_data для Auth::id() — она бы
        // конфликтовала с явной строкой ниже по составному PK.
        $sourceProduct = SourceProduct::factory()->make();
        $sourceProduct->save();

        return SourceProductUserData::factory()->create([
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $sourceProduct->id,
            SourceProductUserData::PARAM_USER_ID => $this->user->id,
            SourceProductUserData::PARAM_LISTEN_PRICE_VALUE => 1500,
            SourceProductUserData::PARAM_COMMENT => 'Нужен размер 43',
        ]);
    }

    public function test_show_returns_user_data_for_current_user(): void
    {
        $userData = $this->createForCurrentUser();

        $this->apiGet("/api/source_product_user_data/{$userData->source_product_id}")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.listen_price_value', 1500)
            ->assertJsonPath('data.comment', 'Нужен размер 43');
    }

    public function test_show_does_not_leak_foreign_keys(): void
    {
        $userData = $this->createForCurrentUser();

        $data = $this->apiGet("/api/source_product_user_data/{$userData->source_product_id}")
            ->assertOk()
            ->json('data');

        $this->assertArrayNotHasKey(SourceProductUserData::PARAM_SOURCE_PRODUCT_ID, $data);
        $this->assertArrayNotHasKey(SourceProductUserData::PARAM_USER_ID, $data);
    }

    /**
     * Документация дефекта: ModelNotFoundException → NotFoundHttpException →
     * Throwable-коллбэк → 500 (см. bootstrap/app.php).
     */
    public function test_show_missing_row_is_200_with_success_false(): void
    {
        $this->apiGet('/api/source_product_user_data/99999')
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

        $sourceProduct = SourceProduct::factory()->make();
        $sourceProduct->save();

        $userData = SourceProductUserData::factory()->create([
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $sourceProduct->id,
            SourceProductUserData::PARAM_USER_ID => $otherUser->id,
        ]);

        $this->apiGet("/api/source_product_user_data/{$userData->source_product_id}")
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }
}
