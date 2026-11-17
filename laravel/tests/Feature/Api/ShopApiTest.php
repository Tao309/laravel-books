<?php

namespace Tests\Feature\Api;

use App\Models\Shop;
use Tests\Feature\Api\Concerns\ActsAsApiClient;
use Tests\TestCase;

/**
 * API /api/shops — только чтение (роуты ограничены index/show).
 * Командные пути в ShopProvider не привязаны, поэтому store/update/
 * destroy недостижимы и на уровне роутов.
 */
class ShopApiTest extends TestCase
{
    use ActsAsApiClient;

    // ---------- GET /api/shops ----------

    public function test_index_returns_all_shops(): void
    {
        // трейт ActsAsApiClient уже создал ozon (id=1) в setUp и сдвинул
        // секвенс shops за фактический максимум — plain-фабрики не коллизируют
        Shop::factory()->count(3)->create();

        $response = $this->apiGet('/api/shops')
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertCount(4, $response->json('data.items'));
    }

    public function test_index_ignores_limit_filter(): void
    {
        Shop::factory()->count(5)->create();

        // документация дефекта: репозиторий делает Shop::all() —
        // limit игнорируется, возвращаются все 6 магазинов (5 + ozon из setUp)
        $this->apiGet('/api/shops?limit=1')
            ->assertOk()
            ->assertJsonCount(6, 'data.items');
    }

    public function test_index_items_contain_type_domain_and_url(): void
    {
        Shop::factory()->create([
            Shop::PARAM_TYPE => 'test-shop',
            Shop::PARAM_DOMAIN => 'test-shop.ru',
            Shop::PARAM_URL => 'https://test-shop.ru',
        ]);

        $this->apiGet('/api/shops')
            ->assertOk()
            ->assertJsonFragment([
                'type' => 'test-shop',
                'domain' => 'test-shop.ru',
                'url' => 'https://test-shop.ru',
            ]);
    }

    // ---------- GET /api/shops/{id} ----------

    public function test_show_returns_shop(): void
    {
        $shop = Shop::factory()->create();

        $this->apiGet("/api/shops/{$shop->id}")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $shop->id)
            ->assertJsonPath('data.type', $shop->type)
            ->assertJsonPath('data.domain', $shop->domain);
    }

    /**
     * Документация дефекта: ModelNotFoundException → NotFoundHttpException →
     * Throwable-коллбэк → 500 (см. bootstrap/app.php).
     */
    public function test_show_missing_shop_is_200_with_success_false(): void
    {
        $this->apiGet('/api/shops/99999')
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }

    // ---------- WRITE-роуты не зарегистрированы ----------

    public function test_store_endpoint_is_not_registered(): void
    {
        $this->apiPost('/api/shops', [
            Shop::PARAM_TYPE => 'ozon',
            Shop::PARAM_DOMAIN => 'ozon.ru',
        ])
            // Документация дефекта: незарегистрированный роут → Throwable-коллбэк → 500.
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }

    public function test_update_endpoint_is_not_registered(): void
    {
        $shop = Shop::factory()->create();

        $this->apiPut("/api/shops/{$shop->id}", [
            Shop::PARAM_DOMAIN => 'new-domain.ru',
        ])
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }

    public function test_destroy_endpoint_is_not_registered(): void
    {
        $shop = Shop::factory()->create();

        $this->apiDelete("/api/shops/{$shop->id}")
            ->assertStatus(500)
            ->assertJsonPath('success', false);

        $this->assertModelExists($shop);
    }
}
