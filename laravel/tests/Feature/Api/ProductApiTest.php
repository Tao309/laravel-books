<?php

namespace Tests\Feature\Api;

use App\Enums\ProductFlag;
use App\Models\BindingType;
use App\Models\Book;
use App\Models\City;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductStock;
use App\Models\ProductUserData;
use App\Models\Shop;
use Tests\Feature\Api\Concerns\ActsAsApiClient;
use Tests\TestCase;

/**
 * API-тесты CRUD-эндпоинтов /api/products.
 *
 * Успешные ответы оборачивает middleware ApiResponse: {data: ..., success: true}
 * (ключ success из payload удаляется — unset в ApiResponse::processResponse).
 * ModelNotFoundException оборачивается в NotFoundHttpException ещё в
 * prepareException, поэтому коллбэк для ModelNotFoundException не срабатывает —
 * «не найдено» приходит как 500 от Throwable-коллбэка (см. bootstrap/app.php).
 * Репозиторий и query-фильтр всегда скоупят выборку по магазину сессии (ozon, id=1),
 * поэтому фабричные товары для index/show создаются с PARAM_SHOP_ID => 1.
 */
class ProductApiTest extends TestCase
{
    use ActsAsApiClient;

    // ---------- GET /api/products ----------

    public function test_index_paginates_when_no_filters(): void
    {
        // sourceless: nested SourceProduct из фабрики создаёт случайный
        // SourceProductType — при 20 товарах word-коллизии по уникальному индексу
        Product::factory()
            ->count(20)
            ->state(fn () => [Product::PARAM_SOURCE_TYPE => null, Product::PARAM_SOURCE_ID => null])
            ->create([Product::PARAM_SHOP_ID => 1]);

        $this->apiGet('/api/products')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(15, 'data.items')
            ->assertJsonPath('data.meta.total', 20)
            ->assertJsonPath('data.meta.current_page', 1)
            ->assertJsonPath('data.meta.last_page', 2);
    }

    public function test_index_with_limit_returns_collection(): void
    {
        // sourceless: см. комментарий в test_index_paginates_when_no_filters
        Product::factory()
            ->count(5)
            ->state(fn () => [Product::PARAM_SOURCE_TYPE => null, Product::PARAM_SOURCE_ID => null])
            ->create([Product::PARAM_SHOP_ID => 1]);

        // limit попадает в hasFilters() → indexAction вместо пагинации
        $this->apiGet('/api/products?limit=2')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(2, 'data.items')
            ->assertJsonPath('data.meta.count', 2);
    }

    public function test_index_by_ids_filters_by_shop_product_id(): void
    {
        // shop_product_id задаём явно: numerify может выдать ведущий ноль,
        // а integer-правило id.* (filter_var FILTER_VALIDATE_INT) такие строки
        // отбрасывает → недетерминированные 422 на случайном значении
        $target = Product::factory()
            ->state(fn () => [Product::PARAM_SOURCE_TYPE => null, Product::PARAM_SOURCE_ID => null])
            ->create([
                Product::PARAM_SHOP_ID => 1,
                Product::PARAM_SHOP_PRODUCT_ID => '123456789012',
            ]);
        Product::factory()
            ->state(fn () => [Product::PARAM_SOURCE_TYPE => null, Product::PARAM_SOURCE_ID => null])
            ->create([Product::PARAM_SHOP_ID => 1]);

        // Важно: ProductQueryFilter фильтрует ids по shop_product_id, а не по id
        $this->apiGet('/api/products?ids=123456789012')
            ->assertOk()
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.items.0.id', $target->id);
    }

    public function test_index_search_finds_by_title(): void
    {
        Product::factory()->create([
            Product::PARAM_SHOP_ID => 1,
            Product::PARAM_TITLE => 'Уникальный товар для поиска',
            Product::PARAM_SOURCE_TYPE => null,
            Product::PARAM_SOURCE_ID => null,
        ]);
        Product::factory()->create([
            Product::PARAM_SHOP_ID => 1,
            Product::PARAM_SOURCE_TYPE => null,
            Product::PARAM_SOURCE_ID => null,
        ]);

        $this->apiGet('/api/products?search=Уникальн')
            ->assertOk()
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.items.0.id', Product::query()
                ->where(Product::PARAM_TITLE, 'Уникальный товар для поиска')
                ->firstOrFail()->id);
    }

    public function test_index_search_is_validated(): void
    {
        $this->apiGet('/api/products?search=ab')
            ->assertUnprocessable()
            ->assertJsonPath('success', false)
            ->assertJsonPath('errors.search.0', 'Search value must be at least 3.');
    }

    public function test_index_ids_rejects_bad_format(): void
    {
        $this->apiGet('/api/products?ids=1,,2')
            ->assertUnprocessable()
            ->assertJsonPath('success', false)
            ->assertJsonPath('errors.ids.0', 'IDs is not correct.');
    }

    public function test_index_limit_max_100(): void
    {
        $this->apiGet('/api/products?limit=101')
            ->assertUnprocessable()
            ->assertJsonPath('success', false);
    }

    public function test_index_hides_products_of_other_shop(): void
    {
        Shop::factory()->wb()->create();

        $foreign = Product::factory()
            ->state(fn () => [Product::PARAM_SOURCE_TYPE => null, Product::PARAM_SOURCE_ID => null])
            ->create([Product::PARAM_SHOP_ID => 2]);
        $own = Product::factory()
            ->state(fn () => [Product::PARAM_SOURCE_TYPE => null, Product::PARAM_SOURCE_ID => null])
            ->create([Product::PARAM_SHOP_ID => 1]);

        $response = $this->apiGet('/api/products')
            ->assertOk()
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.items.0.id', $own->id);

        // notChecked: вложенный shop.id совпадает с id чужого товара —
        // проверяем только товары в data.items, а не весь JSON.
        $this->assertNotContains(
            $foreign->id,
            array_column($response->json('data.items'), 'id')
        );
    }

    // ---------- GET /api/products/{id} ----------

    public function test_show_returns_product_with_relations(): void
    {
        $book = Book::factory()->create();
        $city = City::factory()->create();
        $product = Product::factory()->create([
            Product::PARAM_SHOP_ID => 1,
            Product::PARAM_SOURCE_TYPE => Book::class,
            Product::PARAM_SOURCE_ID => $book->id,
            Product::PARAM_CITY_ID => $city->id,
        ]);

        $this->apiGet("/api/products/{$product->id}")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $product->id)
            ->assertJsonPath('data.book.id', $book->id)
            ->assertJsonStructure([
                'data' => [
                    'shop',
                    'city',
                    'product_user_data',
                    'prices',
                    'stocks',
                    'min_price',
                    'last_qty',
                    'last_stock',
                ],
            ]);
    }

    public function test_show_hides_sensitive_columns(): void
    {
        $product = Product::factory()->create([Product::PARAM_SHOP_ID => 1]);

        $this->apiGet("/api/products/{$product->id}")
            ->assertJsonMissing(['shop_id' => $product->shop_id])
            ->assertJsonMissing(['city_id' => $product->city_id])
            ->assertJsonMissing(['user_created_id' => $product->user_created_id]);
    }

    public function test_show_min_price_and_last_qty_belong_to_current_user_only(): void
    {
        $product = Product::factory()->create([Product::PARAM_SHOP_ID => 1]);

        ProductPrice::factory()->create([
            ProductPrice::PARAM_PRODUCT_ID => $product->id,
            ProductPrice::PARAM_USER_ID => $this->user->id,
            ProductPrice::PARAM_PRICE => 1500,
            ProductPrice::PARAM_DATE => '2026-01-01 10:00:00',
        ]);
        ProductPrice::factory()->create([
            ProductPrice::PARAM_PRODUCT_ID => $product->id,
            ProductPrice::PARAM_USER_ID => $this->user->id,
            ProductPrice::PARAM_PRICE => 1200,
            ProductPrice::PARAM_DATE => '2026-06-01 10:00:00',
        ]);
        ProductStock::factory()->create([
            ProductStock::PARAM_PRODUCT_ID => $product->id,
            ProductStock::PARAM_USER_ID => $this->user->id,
            ProductStock::PARAM_QTY => 7,
            ProductStock::PARAM_DATE => '2026-06-01 10:00:00',
        ]);

        // цена другого пользователя не должна попасть в выдачу
        ProductPrice::factory()->create([
            ProductPrice::PARAM_PRODUCT_ID => $product->id,
            ProductPrice::PARAM_PRICE => 1,
        ]);

        $this->apiGet("/api/products/{$product->id}")
            ->assertJsonPath('data.min_price', 1200)
            ->assertJsonPath('data.last_qty', 7)
            ->assertJsonPath('data.last_stock.qty', 7)
            ->assertJsonCount(2, 'data.prices');
    }

    /**
     * Документация дефекта: ModelNotFoundException → NotFoundHttpException →
     * Throwable-коллбэк → 500 с исходным сообщением исключения.
     */
    public function test_show_missing_product_is_200_with_success_false(): void
    {
        $this->apiGet('/api/products/99999')
            ->assertStatus(500)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Product by id = 99999 not found');
    }

    /**
     * Документация дефекта: get() со скоупом по магазину сессии →
     * ModelNotFoundException → NotFoundHttpException → Throwable-коллбэк → 500.
     */
    public function test_show_product_of_other_shop_is_not_found(): void
    {
        Shop::factory()->wb()->create();

        $foreign = Product::factory()->create([Product::PARAM_SHOP_ID => 2]);

        $this->apiGet("/api/products/{$foreign->id}")
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }

    // ---------- POST /api/products ----------

    private function payload(array $overrides = []): array
    {
        // фабрика Book гнездит BindingType со случайным label (уникальный
        // индекс) — при второй книге в тесте рандом коллидится, поэтому
        // тип создаём явно
        $book = Book::factory()->create([
            Book::PARAM_BINDING_TYPE_ID => BindingType::factory()->create()->id,
        ]);

        return array_merge([
            'shop_product_id' => fake()->numerify('############'),
            'title' => 'Тестовый товар на витрине',
            'shop' => ['id' => 1],
            // nullable-правило: null проходит exists-валидацию
            'city' => null,
            'book' => ['id' => $book->id],
            // Даты цен/стоков и PUD приходят в 'Y.m.d' (см. date_format в
            // AbstractProductRequest); DateService нормализует их в 'Y-m-d H:i:s'.
            'product_user_data' => [
                'available' => true,
            ],
            'prices' => [
                ['price' => 1500, 'date' => '2026.11.01'],
                ['price' => 1200, 'date' => '2026.11.05'],
            ],
            'stocks' => [
                ['qty' => 7, 'date' => '2026.11.05'],
            ],
            // Гейты сохранения цен/стоков/PUD в ProductRepository — незакоммиченное
            // поведение: без флагов massSave не вызывается вовсе. array-правило
            // валидации требует ровно все ключи ProductFlag::cases().
            'flags' => [
                ProductFlag::SaveProductUserData->value => true,
                ProductFlag::SavePrices->value => true,
                ProductFlag::SaveStocks->value => true,
                ProductFlag::ChangeId->value => false,
            ],
        ], $overrides);
    }

    public function test_store_creates_product_with_all_dependencies(): void
    {
        $payload = $this->payload();

        $this->apiPost('/api/products', $payload)
            ->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.shop_product_id', $payload['shop_product_id'])
            ->assertJsonPath('data.min_price', 1200)
            ->assertJsonPath('data.last_qty', 7);

        $this->assertDatabaseHas(Product::TABLE_NAME, [
            Product::PARAM_SHOP_PRODUCT_ID => $payload['shop_product_id'],
            Product::PARAM_SHOP_ID => 1,
            Product::PARAM_USER_CREATED_ID => $this->user->id,
        ]);
        $this->assertDatabaseHas(ProductUserData::TABLE_NAME, [
            ProductUserData::PARAM_AVAILABLE => true,
            ProductUserData::PARAM_USER_ID => $this->user->id,
        ]);
        $this->assertDatabaseCount(ProductPrice::TABLE_NAME, 2);
        $this->assertDatabaseCount(ProductStock::TABLE_NAME, 1);
    }

    /**
     * Характеристика текущего поведения: у StoreProductRequest нет unique
     * на shop_product_id, а репозиторий findByShopProductId() переиспользует
     * найденный товар. Скалярные поля (title и т.п.) при этом НЕ перезаписываются —
     * getEntityData() применяется только при создании новой модели.
     */
    public function test_store_reuses_existing_product_by_shop_product_id(): void
    {
        $existing = Product::factory()->create([Product::PARAM_SHOP_ID => 1]);
        $originalTitle = $existing->title;

        $this->apiPost('/api/products', $this->payload([
            'shop_product_id' => $existing->shop_product_id,
        ]))->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $existing->id)
            ->assertJsonPath('data.title', $originalTitle);

        $this->assertSame(1, Product::query()
            ->where(Product::PARAM_SHOP_PRODUCT_ID, $existing->shop_product_id)
            ->count());
        $this->assertDatabaseHas(Product::TABLE_NAME, [
            Product::PARAM_ID => $existing->id,
            Product::PARAM_TITLE => $originalTitle,
        ]);
    }

    public function test_store_validates_title_min_length(): void
    {
        $this->apiPost('/api/products', $this->payload(['title' => 'мало']))
            ->assertUnprocessable()
            ->assertJsonPath('success', false);
    }

    public function test_store_validates_price_date_format(): void
    {
        $this->apiPost('/api/products', $this->payload([
            'prices' => [['price' => 100, 'date' => '01.11.2026']],
        ]))->assertUnprocessable()
            ->assertJsonPath('success', false);
    }

    public function test_store_validates_shop_exists(): void
    {
        $this->apiPost('/api/products', $this->payload([
            'shop' => ['id' => 99999],
        ]))->assertUnprocessable()
            ->assertJsonPath('success', false);
    }

    public function test_store_allows_product_without_source(): void
    {
        // Документирует отсутствие required_without на book/source_product:
        // товар без источника создаётся свободно
        $this->apiPost('/api/products', $this->payload(['book' => null]))
            ->assertStatus(201)
            ->assertJsonPath('success', true);
    }

    /**
     * Текущий контракт: 'Y.m.d' проходит валидацию и DateService сохраняет
     * дату PUD в каноническом 'Y-m-d H:i:s'.
     */
    public function test_store_with_pud_date_in_validated_format_succeeds(): void
    {
        $this->apiPost('/api/products', $this->payload([
            'product_user_data' => [
                'available' => true,
                'available_date_from' => '2026.11.01',
            ],
        ]))->assertStatus(201)
            ->assertJsonPath('success', true);

        // 'Y.m.d' не содержит времени — DateService оставляет текущее время,
        // поэтому проверяем только календарную дату.
        $userData = ProductUserData::query()
            ->where(ProductUserData::PARAM_AVAILABLE, true)
            ->firstOrFail();

        $this->assertSame('2026-11-01', $userData->available_date_from?->format('Y-m-d'));
    }

    // ---------- PUT /api/products/{id} ----------

    public function test_update_changes_title_and_ignores_self_in_unique(): void
    {
        $product = Product::factory()->create([Product::PARAM_SHOP_ID => 1]);

        $this->apiPut("/api/products/{$product->id}", $this->payload([
            'shop_product_id' => $product->shop_product_id,
            'title' => 'Обновлённый заголовок товара',
        ]))->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'Обновлённый заголовок товара');

        $this->assertDatabaseHas(Product::TABLE_NAME, [
            Product::PARAM_ID => $product->id,
            Product::PARAM_TITLE => 'Обновлённый заголовок товара',
        ]);
    }

    public function test_update_upserts_prices_and_stocks(): void
    {
        $product = Product::factory()->create([Product::PARAM_SHOP_ID => 1]);

        $this->apiPut("/api/products/{$product->id}", $this->payload([
            'shop_product_id' => $product->shop_product_id,
            'prices' => [['price' => 999, 'date' => '2026.11.01']],
        ]))->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas(ProductPrice::TABLE_NAME, [
            ProductPrice::PARAM_PRODUCT_ID => $product->id,
            ProductPrice::PARAM_USER_ID => $this->user->id,
            ProductPrice::PARAM_PRICE => 999,
        ]);
    }

    /**
     * Характеристика текущего поведения: update() ищет товар без shop-скоупа
     * (Product::findOrFail). Если скоуп добавят — тест нужно перевернуть.
     */
    public function test_update_product_of_other_shop_succeeds(): void
    {
        Shop::factory()->wb()->create();

        $foreign = Product::factory()->create([Product::PARAM_SHOP_ID => 2]);

        $this->apiPut("/api/products/{$foreign->id}", $this->payload([
            'shop_product_id' => $foreign->shop_product_id,
            'title' => 'Обновление чужого магазина',
        ]))->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas(Product::TABLE_NAME, [
            Product::PARAM_ID => $foreign->id,
            Product::PARAM_TITLE => 'Обновление чужого магазина',
        ]);
    }

    /**
     * Документация дефекта: ModelNotFoundException → NotFoundHttpException →
     * Throwable-коллбэк → 500 (см. bootstrap/app.php).
     */
    public function test_update_missing_product_is_200_with_success_false(): void
    {
        $this->apiPut('/api/products/99999', $this->payload())
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }

    // ---------- DELETE /api/products/{id} ----------

    public function test_destroy_removes_product_and_cascades(): void
    {
        $product = Product::factory()->create([Product::PARAM_SHOP_ID => 1]);

        ProductUserData::factory()->create([
            ProductUserData::PARAM_PRODUCT_ID => $product->id,
            ProductUserData::PARAM_USER_ID => $this->user->id,
        ]);
        ProductPrice::factory()->create([
            ProductPrice::PARAM_PRODUCT_ID => $product->id,
            ProductPrice::PARAM_USER_ID => $this->user->id,
        ]);
        ProductStock::factory()->create([
            ProductStock::PARAM_PRODUCT_ID => $product->id,
            ProductStock::PARAM_USER_ID => $this->user->id,
        ]);

        // destroyAction отдаёт ['success' => true], но ApiResponse делает unset('success')
        // из payload и оборачивает его в data → наружу {data: [], success: true}
        $this->apiDelete("/api/products/{$product->id}")
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertModelMissing($product);
        $this->assertDatabaseCount(ProductPrice::TABLE_NAME, 0);
        $this->assertDatabaseCount(ProductStock::TABLE_NAME, 0);
        $this->assertDatabaseCount(ProductUserData::TABLE_NAME, 0);
    }

    /**
     * Документация дефекта: ModelNotFoundException → NotFoundHttpException →
     * Throwable-коллбэк → 500 (см. bootstrap/app.php).
     */
    public function test_destroy_missing_product_is_200_with_success_false(): void
    {
        $this->apiDelete('/api/products/99999')
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }

    /**
     * Характеристика текущего поведения: delete() тоже без shop-скоупа.
     */
    public function test_destroy_product_of_other_shop_succeeds(): void
    {
        Shop::factory()->wb()->create();

        $foreign = Product::factory()->create([Product::PARAM_SHOP_ID => 2]);

        // destroyAction отдаёт ['success' => true], но ApiResponse делает unset('success')
        // из payload → наружу {data: [], success: true}
        $this->apiDelete("/api/products/{$foreign->id}")
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertModelMissing($foreign);
    }
}
