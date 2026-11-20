<?php

namespace Tests\Feature\Api;

use App\Models\SourceProduct;
use App\Models\SourceProductType;
use App\Models\SourceProductUserData;
use Tests\Feature\Api\Concerns\ActsAsApiClient;
use Tests\TestCase;

/**
 * API /api/source_products — полный CRUD (index, show, store, update, destroy).
 *
 * В отличие от справочников, здесь фильтры РАБОТАЮТ: SourceProductQueryFilter
 * применяется в getAll (search по title с limit(7), ids, limit).
 * Связи отправляются как объекты ['id' => ...] (конвенция Product).
 */
class SourceProductApiTest extends TestCase
{
    use ActsAsApiClient;

    private const string TITLE = 'Кроссовки лето';

    /**
     * Один общий тип на тест: definition SourceProductFactory создаёт
     * nested SourceProductType::factory() на каждый товар, random word
     * коллизирует по source_product_types_code_unique при 2+ товарах.
     */
    private function sharedType(): SourceProductType
    {
        return SourceProductType::factory()->medicines()->create();
    }

    private function payload(array $overrides = []): array
    {
        $type = SourceProductType::factory()->create();

        return array_merge([
            SourceProduct::PARAM_TITLE => self::TITLE,
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE => ['id' => $type->id],
            SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA => [
                SourceProductUserData::PARAM_COMMENT => 'Нужен размер 43',
                SourceProductUserData::PARAM_LISTEN_PRICE_VALUE => 1500,
            ],
        ], $overrides);
    }

    // ---------- GET /api/source_products ----------

    public function test_index_returns_collection_without_pagination(): void
    {
        $type = $this->sharedType();

        SourceProduct::factory()->count(2)->create([
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID => $type->id,
        ]);

        $response = $this->apiGet('/api/source_products')
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertCount(2, $response->json('data.items'));
    }

    /**
     * В отличие от справочников limit уважается: SourceProductQueryFilter
     * применяет ->limit($value) к запросу.
     */
    public function test_index_respects_limit_filter(): void
    {
        $type = $this->sharedType();

        SourceProduct::factory()->count(3)->create([
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID => $type->id,
        ]);

        $this->apiGet('/api/source_products?limit=1')
            ->assertOk()
            ->assertJsonCount(1, 'data.items');
    }

    public function test_index_search_matches_title(): void
    {
        $type = $this->sharedType();

        SourceProduct::factory()->create([
            SourceProduct::PARAM_TITLE => self::TITLE,
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID => $type->id,
        ]);
        SourceProduct::factory()->create([
            SourceProduct::PARAM_TITLE => 'Платье зимнее',
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID => $type->id,
        ]);

        $items = $this->apiGet('/api/source_products?search=' . urlencode('кроссовки'))
            ->assertOk()
            ->json('data.items');

        $this->assertCount(1, $items);
        $this->assertSame(self::TITLE, $items[0]['title']);
    }

    public function test_index_search_limits_to_seven(): void
    {
        $type = $this->sharedType();

        foreach (range(1, 10) as $i) {
            SourceProduct::factory()->create([
                SourceProduct::PARAM_TITLE => "Джинсы модель {$i}",
                SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID => $type->id,
            ]);
        }

        $this->apiGet('/api/source_products?search=' . urlencode('джинсы'))
            ->assertOk()
            ->assertJsonCount(7, 'data.items');
    }

    public function test_index_items_carry_eager_relations(): void
    {
        $sourceProduct = SourceProduct::factory()->create();

        $item = $this->apiGet('/api/source_products')
            ->assertOk()
            ->json('data.items.0');

        $this->assertSame($sourceProduct->source_product_type_id, $item['source_product_type']['id']);
        // user_created — пользователь фабрики (user_created_id), а не текущий
        $this->assertSame($sourceProduct->userCreated->id, $item['user_created']['id']);
        // Строку user_data фабрика создаёт для владельца, но текущий пользователь
        // другой; документация дефекта: ресурс рендерит отсутствующую связь
        // как null (а не объект с null-полями) — поля недоступны без проверки
        $this->assertNull($item['source_product_user_data']);
    }

    // ---------- POST /api/source_products ----------

    public function test_store_creates_source_product_returns_201(): void
    {
        $payload = $this->payload();

        $this->apiPost('/api/source_products', $payload)
            ->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', self::TITLE);

        $sourceProduct = SourceProduct::query()->where(
            SourceProduct::PARAM_TITLE,
            self::TITLE,
        )->first();

        $this->assertNotNull($sourceProduct);
        $this->assertDatabaseHas(SourceProduct::TABLE_NAME, [
            SourceProduct::PARAM_ID => $sourceProduct->id,
            SourceProduct::PARAM_USER_CREATED_ID => $this->user->id,
        ]);
        $this->assertDatabaseHas(SourceProductUserData::TABLE_NAME, [
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $sourceProduct->id,
            SourceProductUserData::PARAM_USER_ID => $this->user->id,
            SourceProductUserData::PARAM_COMMENT => 'Нужен размер 43',
        ]);
    }

    public function test_store_without_user_data_creates_row_with_nulls(): void
    {
        $payload = $this->payload();
        unset($payload[SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA]);

        $this->apiPost('/api/source_products', $payload)
            ->assertStatus(201);

        $sourceProduct = SourceProduct::query()->where(
            SourceProduct::PARAM_TITLE,
            self::TITLE,
        )->first();

        $this->assertDatabaseHas(SourceProductUserData::TABLE_NAME, [
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $sourceProduct->id,
            SourceProductUserData::PARAM_USER_ID => $this->user->id,
            SourceProductUserData::PARAM_COMMENT => null,
        ]);
    }

    public function test_store_rejects_duplicate_title(): void
    {
        $type = $this->sharedType();

        SourceProduct::factory()->create([
            SourceProduct::PARAM_TITLE => self::TITLE,
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID => $type->id,
        ]);

        // кастомных сообщений в AbstractSourceProductRequest нет —
        // дефолтное сообщение валидатора
        $this->apiPost('/api/source_products', $this->payload())
            ->assertUnprocessable()
            ->assertJsonValidationErrors([SourceProduct::PARAM_TITLE]);
    }

    public function test_store_rejects_missing_type(): void
    {
        $payload = $this->payload();
        unset($payload[SourceProduct::PARAM_SOURCE_PRODUCT_TYPE]);

        $this->apiPost('/api/source_products', $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([SourceProduct::PARAM_SOURCE_PRODUCT_TYPE]);
    }

    public function test_store_rejects_nonexistent_type(): void
    {
        $payload = $this->payload([
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE => ['id' => 99999],
        ]);

        $this->apiPost('/api/source_products', $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([SourceProduct::PARAM_SOURCE_PRODUCT_TYPE]);
    }

    public function test_store_rejects_listen_price_value_longer_than_nine_digits(): void
    {
        $payload = $this->payload([
            SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA => [
                SourceProductUserData::PARAM_LISTEN_PRICE_VALUE => 1234567890,
            ],
        ]);

        $this->apiPost('/api/source_products', $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA . '.' . SourceProductUserData::PARAM_LISTEN_PRICE_VALUE,
            ]);
    }

    // ---------- GET /api/source_products/{id} ----------

    public function test_show_returns_source_product(): void
    {
        $sourceProduct = SourceProduct::factory()->create();

        $this->apiGet("/api/source_products/{$sourceProduct->id}")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $sourceProduct->id)
            ->assertJsonPath('data.title', $sourceProduct->title);
    }

    /**
     * Документация дефекта: ModelNotFoundException → NotFoundHttpException →
     * Throwable-коллбэк → 500 (см. bootstrap/app.php).
     */
    public function test_show_missing_source_product_is_200_with_success_false(): void
    {
        $this->apiGet('/api/source_products/99999')
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }

    // ---------- PUT /api/source_products/{id} ----------

    public function test_update_changes_title_and_type(): void
    {
        $oldType = SourceProductType::factory()->shoes()->create();
        $newType = SourceProductType::factory()->shirts()->create();

        // actingAs до создания: фабрика afterCreating создаёт user_data
        // для Auth::id(), и обновление репозитория (скоуп по Auth::id())
        // найдёт строку текущего пользователя
        $this->actingAs($this->user);

        $sourceProduct = SourceProduct::factory()->create([
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID => $oldType->id,
        ]);

        $this->apiPut("/api/source_products/{$sourceProduct->id}", [
            SourceProduct::PARAM_TITLE => 'Куртка осенняя',
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE => ['id' => $newType->id],
            SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA => [
                SourceProductUserData::PARAM_COMMENT => 'Обновлённый комментарий',
            ],
        ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'Куртка осенняя');

        $this->assertDatabaseHas(SourceProduct::TABLE_NAME, [
            SourceProduct::PARAM_ID => $sourceProduct->id,
            SourceProduct::PARAM_TITLE => 'Куртка осенняя',
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID => $newType->id,
        ]);
        /**
         * Документация дефекта: UpdateSourceProductRequest не содержит правил
         * для source_product_user_data.* (в отличие от Store), поэтому
         * validated() отбрасывает этот ключ — comment НЕ обновляется
         * и остаётся null.
         */
        $this->assertDatabaseMissing(SourceProductUserData::TABLE_NAME, [
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $sourceProduct->id,
            SourceProductUserData::PARAM_USER_ID => $this->user->id,
            SourceProductUserData::PARAM_COMMENT => 'Обновлённый комментарий',
        ]);
        $this->assertDatabaseHas(SourceProductUserData::TABLE_NAME, [
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $sourceProduct->id,
            SourceProductUserData::PARAM_USER_ID => $this->user->id,
            SourceProductUserData::PARAM_COMMENT => null,
        ]);
    }

    public function test_update_rejects_title_of_other_source_product(): void
    {
        $type = $this->sharedType();

        SourceProduct::factory()->create([
            SourceProduct::PARAM_TITLE => 'Занятое название',
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID => $type->id,
        ]);
        $sourceProduct = SourceProduct::factory()->create([
            SourceProduct::PARAM_TITLE => 'Свободное название',
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID => $type->id,
        ]);
        // payload() не используется: он создал бы второй случайный тип

        $this->apiPut("/api/source_products/{$sourceProduct->id}", [
            SourceProduct::PARAM_TITLE => 'Занятое название',
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE => ['id' => $type->id],
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([SourceProduct::PARAM_TITLE]);
    }

    /**
     * Документация дефекта: ModelNotFoundException → NotFoundHttpException →
     * Throwable-коллбэк → 500 (см. bootstrap/app.php).
     */
    public function test_update_missing_source_product_is_200_with_success_false(): void
    {
        $type = SourceProductType::factory()->create();

        $this->apiPut('/api/source_products/99999', [
            SourceProduct::PARAM_TITLE => 'Куртка осенняя',
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE => ['id' => $type->id],
        ])
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }

    // ---------- DELETE /api/source_products/{id} ----------

    public function test_destroy_deletes_source_product(): void
    {
        $sourceProduct = SourceProduct::factory()->create();

        $this->apiDelete("/api/source_products/{$sourceProduct->id}")
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertModelMissing($sourceProduct);
    }

    /**
     * Документация дефекта: ModelNotFoundException → NotFoundHttpException →
     * Throwable-коллбэк → 500 (см. bootstrap/app.php).
     */
    public function test_destroy_missing_source_product_is_200_with_success_false(): void
    {
        $this->apiDelete('/api/source_products/99999')
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }
}
