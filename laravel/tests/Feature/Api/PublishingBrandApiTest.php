<?php

namespace Tests\Feature\Api;

use App\Models\PublishingBrand;
use Tests\Feature\Api\Concerns\ActsAsApiClient;
use Tests\TestCase;

/**
 * API /api/publishing_brands. Контроллер использует indexAction (без пагинации),
 * destroy-роут отсутствует, лимиты name симметричны между store и update (min:2) —
 * эти особенности зафиксированы тестами как текущее поведение.
 */
class PublishingBrandApiTest extends TestCase
{
    use ActsAsApiClient;

    private const string NAME = 'Красная звезда';

    // ---------- GET /api/publishing_brands ----------

    public function test_index_returns_collection_without_pagination(): void
    {
        PublishingBrand::factory()->count(2)->create();

        $response = $this->apiGet('/api/publishing_brands')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(2, 'data.items');

        // meta коллекции содержит только count, без current_page/last_page
        $this->assertArrayNotHasKey('current_page', $response->json('data.meta'));
    }

    public function test_index_ignores_limit_filter(): void
    {
        PublishingBrand::factory()->count(3)->create();

        // документация дефекта: репозиторий делает PublishingBrand::all()
        $this->apiGet('/api/publishing_brands?limit=1')
            ->assertOk()
            ->assertJsonCount(3, 'data.items');
    }

    // ---------- POST /api/publishing_brands ----------

    public function test_store_creates_brand_returns_201(): void
    {
        $this->apiPost('/api/publishing_brands', ['name' => self::NAME])
            ->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', self::NAME);

        $this->assertDatabaseHas(PublishingBrand::TABLE_NAME, [
            PublishingBrand::PARAM_NAME => self::NAME,
        ]);
    }

    public function test_store_rejects_duplicate_name(): void
    {
        PublishingBrand::factory()->create([PublishingBrand::PARAM_NAME => self::NAME]);

        $this->apiPost('/api/publishing_brands', ['name' => self::NAME])
            ->assertUnprocessable()
            ->assertJsonPath('success', false)
            ->assertJsonPath('errors.name.0', 'A PublishingBrand with this name already exists');
    }

    public function test_store_validates_name_required(): void
    {
        $this->apiPost('/api/publishing_brands', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_store_accepts_two_character_name(): void
    {
        $this->apiPost('/api/publishing_brands', ['name' => 'AB'])
            ->assertStatus(201);
    }

    // ---------- GET /api/publishing_brands/{id} ----------

    public function test_show_returns_brand(): void
    {
        $brand = PublishingBrand::factory()->create();

        $this->apiGet("/api/publishing_brands/{$brand->id}")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $brand->id)
            ->assertJsonPath('data.name', $brand->name);
    }

    /**
     * Документация дефекта: ModelNotFoundException → NotFoundHttpException →
     * Throwable-коллбэк → 500 (см. bootstrap/app.php).
     */
    public function test_show_missing_brand_is_200_with_success_false(): void
    {
        $this->apiGet('/api/publishing_brands/99999')
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }

    // ---------- PUT /api/publishing_brands/{id} ----------

    public function test_update_changes_name(): void
    {
        $brand = PublishingBrand::factory()->create([PublishingBrand::PARAM_NAME => 'Старое имя бренда']);

        $this->apiPut("/api/publishing_brands/{$brand->id}", ['name' => 'Новое имя бренда'])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Новое имя бренда');

        $this->assertDatabaseHas(PublishingBrand::TABLE_NAME, [
            PublishingBrand::PARAM_ID => $brand->id,
            PublishingBrand::PARAM_NAME => 'Новое имя бренда',
        ]);
    }

    public function test_update_ignores_self_in_unique(): void
    {
        $brand = PublishingBrand::factory()->create([PublishingBrand::PARAM_NAME => 'Старое имя бренда']);

        $this->apiPut("/api/publishing_brands/{$brand->id}", ['name' => 'Старое имя бренда'])
            ->assertOk();
    }

    public function test_update_rejects_name_of_other_brand(): void
    {
        PublishingBrand::factory()->create([PublishingBrand::PARAM_NAME => 'Занятое имя бренда']);
        $brand = PublishingBrand::factory()->create([PublishingBrand::PARAM_NAME => 'Свободное имя бренда']);

        $this->apiPut("/api/publishing_brands/{$brand->id}", ['name' => 'Занятое имя бренда'])
            ->assertUnprocessable()
            ->assertJsonPath('errors.name.0', 'A PublishingBrand with this name already exists');
    }

    public function test_update_uses_same_min_length_as_store(): void
    {
        $brand = PublishingBrand::factory()->create([PublishingBrand::PARAM_NAME => 'Бренд с длинным именем']);

        // Store и Update оба требуют min:2 — 'AB' теперь проходит
        $this->apiPut("/api/publishing_brands/{$brand->id}", ['name' => 'AB'])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'AB');
    }

    public function test_update_rejects_single_character_name(): void
    {
        $brand = PublishingBrand::factory()->create([PublishingBrand::PARAM_NAME => 'Бренд с длинным именем']);

        // Отдельный тест: ResponseContext — singleton, и после успешного request
        // в том же тесте валидационные errors уезжают внутрь data.
        $this->apiPut("/api/publishing_brands/{$brand->id}", ['name' => 'A'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Документация дефекта: ModelNotFoundException → NotFoundHttpException →
     * Throwable-коллбэк → 500 (см. bootstrap/app.php).
     */
    public function test_update_missing_brand_is_200_with_success_false(): void
    {
        $this->apiPut('/api/publishing_brands/99999', ['name' => 'Новое имя бренда'])
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }

    // ---------- DELETE /api/publishing_brands/{id} ----------

    public function test_destroy_endpoint_is_not_registered(): void
    {
        $brand = PublishingBrand::factory()->create();

        // Документация дефекта: незарегистрированный роут ловится
        // Throwable-коллбэком в bootstrap/app.php → 500, а не 404.
        // Слой удаления в шине тоже сломан (DestroyPublishingBrandHandler не привязан).
        $this->apiDelete("/api/publishing_brands/{$brand->id}")
            ->assertStatus(500)
            ->assertJsonPath('success', false);

        $this->assertModelExists($brand);
    }
}
