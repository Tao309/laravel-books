<?php

namespace Tests\Feature\Api;

use App\Models\Book;
use App\Models\BookUserData;
use App\Models\Product;
use App\Models\ProductUserData;
use App\Models\Shop;
use App\Models\SourceProduct;
use App\Models\SourceProductUserData;
use Tests\Feature\Api\Concerns\ActsAsApiClient;
use Tests\TestCase;

/**
 * API-тесты экшен-эндпоинтов Product: mass-save, archive, link/unlink.
 *
 * ApiException рендерится со статусом getCode() — дефолт 400.
 * Архив-эндпоинт возвращает модель Product, но благодаря атрибуту
 * #[UseResource(ProductResource::class)] она рендерится ресурсом —
 * ключ связи snake_case product_user_data, как и у остальных ресурсов.
 */
class ProductActionsApiTest extends TestCase
{
    use ActsAsApiClient;

    /**
     * Продукт гарантированно без источника: definition фабрики
     * случайен (Book / SourceProduct / null), для link/unlink нужна детерминированность.
     */
    private function createProductWithoutSource(array $attributes = []): Product
    {
        return Product::factory()
            ->state(fn () => [
                Product::PARAM_SOURCE_TYPE => null,
                Product::PARAM_SOURCE_ID => null,
            ])
            ->create($attributes);
    }

    // ---------- POST /api/products/mass-save ----------

    /**
     * Документация дефекта: 'required' на items.*.id перебивает
     * 'required_without' (проверяется раньше и безусловно) — создание
     * товара без id НЕВАЛИДНО: весь запрос отбрасывается на 422,
     * обновление существующего товара тоже не выполняется.
     */
    public function test_mass_save_rejects_item_without_id_as_unprocessable(): void
    {
        // Явный shop_id: ozon-магазин (id=1) уже создан в setUp трейта,
        // а секвенс shops не сдвинулся (id задан явно) — nested Shop::factory()
        // из ProductFactory получил бы дубликат shops_pkey.
        $existing = Product::factory()->create([Product::PARAM_SHOP_ID => 1]);

        $this->apiPost('/api/products/mass-save', [
            'items' => [
                [
                    'id' => $existing->id,
                    'shop_product_id' => $existing->shop_product_id,
                    'title' => 'Массово обновлённый товар',
                    'shop' => ['id' => 1],
                ],
                [
                    'shop_product_id' => '123456789012',
                    'title' => 'Массово созданный товар',
                    'shop' => ['id' => 1],
                ],
            ],
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['items.1.id']);

        // валидация отбросила весь запрос до контроллера: обновление не выполнено
        $this->assertDatabaseMissing(Product::TABLE_NAME, [
            Product::PARAM_ID => $existing->id,
            Product::PARAM_TITLE => 'Массово обновлённый товар',
        ]);
    }

    public function test_mass_save_commits_chunks_independently(): void
    {
        // Валидация требует items.*.id ('required' идёт перед
        // 'required_without' и делает его мёртвым кодом), поэтому
        // создание через mass-save невозможно — обновляем существующие.
        $products = Product::factory()
            ->count(10)
            ->state(fn () => [Product::PARAM_SOURCE_TYPE => null, Product::PARAM_SOURCE_ID => null])
            ->create([Product::PARAM_SHOP_ID => 1])
            ->values();

        $items = $products
            ->map(fn (Product $p, $i) => [
                'id' => $p->id,
                'shop_product_id' => $p->shop_product_id,
                'title' => "Чанк товар номер {$i}",
                'shop' => ['id' => 1],
            ])
            ->values()
            ->all();

        // 11-й элемент: транзакция второго чанка падает на несуществующем id
        $items[] = [
            'id' => 99999,
            'shop_product_id' => 'should-fail',
            'title' => 'Товар с ошибкой',
            'shop' => ['id' => 1],
        ];

        // Документация дефекта: ApiController::toJson() безусловно делает
        // setSuccess(true) ПОСЛЕ того, как контроллер выставил setSuccess(false),
        // поэтому success всегда true даже при ошибках чанков; glue-message
        // уходит в 'message', а 'error_messages' вырезается из payload.
        // saved_count/items пустые: $ids/$products сбрасываются после commit().
        $this->apiPost('/api/products/mass-save', ['items' => $items])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.products_count', 11)
            ->assertJsonPath('data.errors_count', 1)
            ->assertJsonPath('data.saved_count', 0)
            ->assertJsonPath('message', fn (string $message) => str_contains($message, '99999'));

        // чанк 1 закоммичен
        $this->assertDatabaseHas(Product::TABLE_NAME, [
            Product::PARAM_ID => $products[0]->id,
            Product::PARAM_TITLE => 'Чанк товар номер 0',
        ]);
        $this->assertDatabaseHas(Product::TABLE_NAME, [
            Product::PARAM_ID => $products[9]->id,
            Product::PARAM_TITLE => 'Чанк товар номер 9',
        ]);
        // чанк 2 откачен
        $this->assertDatabaseMissing(Product::TABLE_NAME, [
            Product::PARAM_TITLE => 'Товар с ошибкой',
        ]);
    }

    public function test_mass_save_requires_id_or_shop_product_id(): void
    {
        $this->apiPost('/api/products/mass-save', [
            'items' => [[
                'title' => 'Товар без идентификатора',
                'shop' => ['id' => 1],
            ]],
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['items.0.shop_product_id']);
    }

    public function test_mass_save_validates_items_max_200(): void
    {
        $this->apiPost('/api/products/mass-save', [
            'items' => array_fill(0, 201, [
                'shop_product_id' => 'abc12',
                'title' => 'Тестовый товар на витрине',
                'shop' => ['id' => 1],
            ]),
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['items']);
    }

    // ---------- POST /api/products/archive ----------

    public function test_archive_updates_product_user_data(): void
    {
        // findByShopProduct() скоупится по магазину сессии (ozon, id=1)
        $product = Product::factory()->create([Product::PARAM_SHOP_ID => 1]);

        ProductUserData::factory()->create([
            ProductUserData::PARAM_PRODUCT_ID => $product->id,
            ProductUserData::PARAM_USER_ID => $this->user->id,
        ]);

        $this->apiPost('/api/products/archive', [
            'shop_product_id' => $product->shop_product_id,
            'is_archive' => true,
        ])->assertOk()
            ->assertJsonPath('success', true)
            // #[UseResource] на модели Product: response()->json($model)
            // рендерится через ProductResource — ключ snake_case
            ->assertJsonPath('data.product_user_data.is_archive', true);

        $this->assertDatabaseHas(ProductUserData::TABLE_NAME, [
            ProductUserData::PARAM_PRODUCT_ID => $product->id,
            ProductUserData::PARAM_USER_ID => $this->user->id,
            ProductUserData::PARAM_IS_ARCHIVE => true,
        ]);
    }

    public function test_archive_missing_product_hits_model_not_found(): void
    {
        // findByShopProduct() использует firstOrFail() → ModelNotFoundException.
        // Документация дефекта: исключение оборачивается в NotFoundHttpException
        // ещё в prepareException → Throwable-коллбэк → 500, а ветка
        // require_to_create и сообщение 'Product is not exists' недостижимы.
        $this->apiPost('/api/products/archive', [
            'shop_product_id' => 'no-such-product',
            'is_archive' => true,
        ])->assertStatus(500)
            ->assertJsonPath('success', false);
    }

    public function test_archive_validates_is_archive_required(): void
    {
        $this->apiPost('/api/products/archive', [
            'shop_product_id' => 'some-id-12',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['is_archive']);
    }

    public function test_archive_validates_shop_product_id_min_length(): void
    {
        $this->apiPost('/api/products/archive', [
            'shop_product_id' => 'abc',
            'is_archive' => true,
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['shop_product_id']);
    }

    // ---------- POST /api/products/link_book/{product_id}/{book_id} ----------

    public function test_link_book_associates_and_creates_book_user_data(): void
    {
        $product = $this->createProductWithoutSource([Product::PARAM_SHOP_ID => 1]);
        $book = Book::factory()->create();

        $this->apiPost("/api/products/link_book/{$product->id}/{$book->id}")
            ->assertOk()
            ->assertJsonPath('success', true);

        // morph map не настроен: source_type — FQCN
        $this->assertDatabaseHas(Product::TABLE_NAME, [
            Product::PARAM_ID => $product->id,
            Product::PARAM_SOURCE_TYPE => Book::class,
            Product::PARAM_SOURCE_ID => $book->id,
        ]);
        $this->assertDatabaseHas(BookUserData::TABLE_NAME, [
            BookUserData::PARAM_BOOK_ID => $book->id,
            BookUserData::PARAM_USER_ID => $this->user->id,
        ]);
    }

    public function test_link_book_conflict_returns_400(): void
    {
        // get() скоупится по магазину сессии — товар нужен в ozon (id=1),
        // иначе ModelNotFoundException → 500 вместо ApiException 400
        $linked = Product::factory()->forBook()->create([Product::PARAM_SHOP_ID => 1]);
        $book = Book::factory()->create();

        $this->apiPost("/api/products/link_book/{$linked->id}/{$book->id}")
            ->assertStatus(400)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Product already has Book or SourceProduct');
    }

    public function test_link_book_missing_book_returns_400(): void
    {
        $product = $this->createProductWithoutSource([Product::PARAM_SHOP_ID => 1]);

        $this->apiPost("/api/products/link_book/{$product->id}/99999")
            ->assertStatus(400)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Not found Book by id = 99999');
    }

    // ---------- POST /api/products/link_source_product/{product_id}/{source_product_id} ----------

    public function test_link_source_product_associates(): void
    {
        $product = $this->createProductWithoutSource([Product::PARAM_SHOP_ID => 1]);
        $sourceProduct = SourceProduct::factory()->create();

        $this->apiPost("/api/products/link_source_product/{$product->id}/{$sourceProduct->id}")
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas(Product::TABLE_NAME, [
            Product::PARAM_ID => $product->id,
            Product::PARAM_SOURCE_TYPE => SourceProduct::class,
            Product::PARAM_SOURCE_ID => $sourceProduct->id,
        ]);
        $this->assertDatabaseHas(SourceProductUserData::TABLE_NAME, [
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $sourceProduct->id,
            SourceProductUserData::PARAM_USER_ID => $this->user->id,
        ]);
    }

    public function test_link_source_product_conflict_returns_400(): void
    {
        // get() скоупится по магазину сессии — товар нужен в ozon (id=1)
        $linked = Product::factory()->forSourceProduct()->create([Product::PARAM_SHOP_ID => 1]);
        $sourceProduct = SourceProduct::factory()->create();

        $this->apiPost("/api/products/link_source_product/{$linked->id}/{$sourceProduct->id}")
            ->assertStatus(400)
            ->assertJsonPath('message', 'Product already has Book or SourceProduct');
    }

    // ---------- POST /api/products/unlink/{product_id} ----------

    public function test_unlink_clears_source(): void
    {
        // get() скоупится по магазину сессии — товар нужен в ozon (id=1)
        $product = Product::factory()->forBook()->create([Product::PARAM_SHOP_ID => 1]);

        $this->apiPost("/api/products/unlink/{$product->id}")
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas(Product::TABLE_NAME, [
            Product::PARAM_ID => $product->id,
            Product::PARAM_SOURCE_TYPE => null,
            Product::PARAM_SOURCE_ID => null,
        ]);
    }

    public function test_unlink_without_source_returns_400(): void
    {
        $product = $this->createProductWithoutSource([Product::PARAM_SHOP_ID => 1]);

        $this->apiPost("/api/products/unlink/{$product->id}")
            ->assertStatus(400)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Product already no has Book or SourceProduct');
    }

    // ---------- изоляция по магазину ----------

    public function test_link_book_rejects_foreign_shop_product(): void
    {
        Shop::factory()->wb()->create();

        $foreign = $this->createProductWithoutSource([Product::PARAM_SHOP_ID => 2]);
        $book = Book::factory()->create();

        // productRepo->get() со скоупом по сессии → ModelNotFoundException →
        // NotFoundHttpException → Throwable-коллбэк → 500
        $this->apiPost("/api/products/link_book/{$foreign->id}/{$book->id}")
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }
}
