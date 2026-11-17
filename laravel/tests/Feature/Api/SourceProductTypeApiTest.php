<?php

namespace Tests\Feature\Api;

use App\Models\SourceProduct;
use App\Models\SourceProductType;
use Tests\Feature\Api\Concerns\ActsAsApiClient;
use Tests\TestCase;

/**
 * API /api/source_product_types — полный CRUD, кроме show:
 * сервис шлёт GetSourceProductTypeByIdQuery, а хендлер назван
 * GetSourceProductByIdTypeHandler → HandlerNotFoundException → HTTP 500.
 * Уникальность code/name дублируется в БД.
 */
class SourceProductTypeApiTest extends TestCase
{
    use ActsAsApiClient;

    private const string CODE = 'shoes';

    private const string NAME = 'Обувь';

    // ---------- GET /api/source_product_types ----------

    public function test_index_returns_collection_without_pagination(): void
    {
        SourceProductType::factory()->count(2)->create();

        $response = $this->apiGet('/api/source_product_types')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(2, 'data.items');

        // meta коллекции содержит только count, без current_page/last_page
        $this->assertArrayNotHasKey('current_page', $response->json('data.meta'));
    }

    public function test_index_ignores_limit_filter(): void
    {
        SourceProductType::factory()->count(3)->create();

        // документация дефекта: репозиторий делает SourceProductType::all()
        $this->apiGet('/api/source_product_types?limit=1')
            ->assertOk()
            ->assertJsonCount(3, 'data.items');
    }

    // ---------- POST /api/source_product_types ----------

    public function test_store_creates_type_returns_201(): void
    {
        $this->apiPost('/api/source_product_types', [
            SourceProductType::PARAM_CODE => self::CODE,
            SourceProductType::PARAM_NAME => self::NAME,
        ])
            ->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.code', self::CODE)
            ->assertJsonPath('data.name', self::NAME);

        $this->assertDatabaseHas(SourceProductType::TABLE_NAME, [
            SourceProductType::PARAM_CODE => self::CODE,
            SourceProductType::PARAM_NAME => self::NAME,
        ]);
    }

    public function test_store_rejects_duplicate_code(): void
    {
        SourceProductType::factory()->create([SourceProductType::PARAM_CODE => self::CODE]);

        $this->apiPost('/api/source_product_types', [
            SourceProductType::PARAM_CODE => self::CODE,
            SourceProductType::PARAM_NAME => self::NAME,
        ])
            ->assertUnprocessable()
            ->assertJsonPath('success', false)
            ->assertJsonPath('errors.code.0', 'A SourceProductType with this code already exists');
    }

    public function test_store_rejects_duplicate_name(): void
    {
        SourceProductType::factory()->create([
            SourceProductType::PARAM_CODE => 'other-code',
            SourceProductType::PARAM_NAME => self::NAME,
        ]);

        $this->apiPost('/api/source_product_types', [
            SourceProductType::PARAM_CODE => self::CODE,
            SourceProductType::PARAM_NAME => self::NAME,
        ])
            ->assertUnprocessable()
            ->assertJsonPath('errors.name.0', 'A SourceProductType with this name already exists');
    }

    public function test_store_validates_required_fields(): void
    {
        $this->apiPost('/api/source_product_types', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                SourceProductType::PARAM_CODE,
                SourceProductType::PARAM_NAME,
            ]);
    }

    public function test_store_rejects_code_longer_than_fifty(): void
    {
        $this->apiPost('/api/source_product_types', [
            SourceProductType::PARAM_CODE => str_repeat('x', 51),
            SourceProductType::PARAM_NAME => self::NAME,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([SourceProductType::PARAM_CODE]);
    }

    // ---------- GET /api/source_product_types/{id} ----------

    /**
     * Документация дефекта: несоответствие имени запроса и хендлера →
     * HandlerNotFoundException → 500 вместо данных типа.
     */
    public function test_show_missing_type_is_500(): void
    {
        $type = SourceProductType::factory()->create();

        $this->apiGet("/api/source_product_types/{$type->id}")
            ->assertStatus(500);
    }

    // ---------- PUT /api/source_product_types/{id} ----------

    public function test_update_changes_code_and_name(): void
    {
        $type = SourceProductType::factory()->create([SourceProductType::PARAM_CODE => 'old-code']);

        $this->apiPut("/api/source_product_types/{$type->id}", [
            SourceProductType::PARAM_CODE => 'new-code',
            SourceProductType::PARAM_NAME => 'Новое название типа',
        ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.code', 'new-code')
            ->assertJsonPath('data.name', 'Новое название типа');

        $this->assertDatabaseHas(SourceProductType::TABLE_NAME, [
            SourceProductType::PARAM_ID => $type->id,
            SourceProductType::PARAM_CODE => 'new-code',
        ]);
    }

    public function test_update_ignores_self_in_unique(): void
    {
        $type = SourceProductType::factory()->create([SourceProductType::PARAM_CODE => self::CODE]);

        $this->apiPut("/api/source_product_types/{$type->id}", [
            SourceProductType::PARAM_CODE => self::CODE,
            SourceProductType::PARAM_NAME => $type->name,
        ])
            ->assertOk();
    }

    public function test_update_rejects_code_of_other_type(): void
    {
        SourceProductType::factory()->create([SourceProductType::PARAM_CODE => 'busy-code']);
        $type = SourceProductType::factory()->create([SourceProductType::PARAM_CODE => 'free-code']);

        $this->apiPut("/api/source_product_types/{$type->id}", [
            SourceProductType::PARAM_CODE => 'busy-code',
            SourceProductType::PARAM_NAME => $type->name,
        ])
            ->assertUnprocessable()
            ->assertJsonPath('errors.code.0', 'A SourceProductType with this code already exists');
    }

    /**
     * Документация дефекта: ModelNotFoundException → NotFoundHttpException →
     * Throwable-коллбэк → 500 (см. bootstrap/app.php).
     */
    public function test_update_missing_type_is_200_with_success_false(): void
    {
        $this->apiPut('/api/source_product_types/99999', [
            SourceProductType::PARAM_CODE => 'new-code',
            SourceProductType::PARAM_NAME => 'Новое название типа',
        ])
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }

    // ---------- DELETE /api/source_product_types/{id} ----------

    public function test_destroy_deletes_type(): void
    {
        $type = SourceProductType::factory()->create();

        $this->apiDelete("/api/source_product_types/{$type->id}")
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertModelMissing($type);
    }

    /**
     * Документация дефекта: ModelNotFoundException → NotFoundHttpException →
     * Throwable-коллбэк → 500 (см. bootstrap/app.php).
     */
    public function test_destroy_missing_type_is_200_with_success_false(): void
    {
        $this->apiDelete('/api/source_product_types/99999')
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }

    /**
     * FK cascade в миграции source_products: удаление типа каскадно
     * удаляет зависимые source_products (и их user_data каскадом).
     */
    public function test_destroy_cascades_to_source_products(): void
    {
        $type = SourceProductType::factory()->create();
        $sourceProduct = SourceProduct::factory()->create([
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID => $type->id,
        ]);

        $this->apiDelete("/api/source_product_types/{$type->id}")
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertModelMissing($type);
        $this->assertModelMissing($sourceProduct);
        $this->assertSame(0, SourceProduct::query()->count());
    }
}
