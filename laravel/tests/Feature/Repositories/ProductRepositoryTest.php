<?php

namespace Tests\Feature\Repositories;

use App\CQRS\Queries\Products\GetProductsPaginateQuery;
use App\CQRS\Queries\Products\GetProductsQuery;
use App\DTOs\CollectionQueryDto;
use App\DTOs\ProductDTO;
use App\Enums\ProductFlag;
use App\Models\Book;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductStock;
use App\Models\ProductUserData;
use App\Models\Shop;
use App\Models\User;
use App\Repositories\ProductRepository;
use App\Services\SessionShopService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

/**
 * ProductRepository: create/update/get/getAll/getAllPaginate и гейты
 * addPrices/addStocks по ProductFlag. Сессионный магазин — singleton
 * SessionShopService (ozon id=1); все Product::factory() — с явным
 * PARAM_SHOP_ID, чтобы nested Shop::factory() не сдвигал секвенс shops.
 */
class ProductRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private ProductRepository $repo;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $shop = Shop::factory()->ozon()->create();
        Shop::factory()->wb()->create();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->app->make(SessionShopService::class)->setShop($shop);

        $this->repo = $this->app->make(ProductRepository::class);
    }

    /**
     * Имена параметров конструктора ProductDTO НЕ совпадают с PARAM_*,
     * поэтому собираем аргументы camelCase-именованными аргументами.
     */
    private function dto(array $overrides = []): ProductDTO
    {
        $args = array_merge([
            'shopProductId' => '123456789012',
            'shopProductCode' => null,
            'title' => 'Кроссовки лето',
            'shop' => [Shop::PARAM_ID => 1],
            'city' => null,
            'book' => null,
            'sourceProduct' => null,
            'productUserData' => [ProductUserData::PARAM_AVAILABLE => true],
            'prices' => [],
            'stocks' => [],
            'flags' => [],
        ], $overrides);

        return new ProductDTO(...$args);
    }

    private function price(Product $product, int $price, string $date = '2026-01-01 10:00:00'): ProductPrice
    {
        return ProductPrice::factory()->create([
            ProductPrice::PARAM_PRODUCT_ID => $product->id,
            ProductPrice::PARAM_USER_ID => $this->user->id,
            ProductPrice::PARAM_PRICE => $price,
            ProductPrice::PARAM_DATE => $date,
        ]);
    }

    private function sourcedProduct(Book $book, array $attributes = []): Product
    {
        return Product::factory()->create([
            Product::PARAM_SHOP_ID => 1,
            Product::PARAM_SOURCE_TYPE => $book::class,
            Product::PARAM_SOURCE_ID => $book->id,
            ...$attributes,
        ]);
    }

    public function test_create_persists_product_and_user_data(): void
    {
        $product = $this->repo->create($this->dto());

        $this->assertDatabaseHas(Product::TABLE_NAME, [
            Product::PARAM_ID => $product->id,
            Product::PARAM_SHOP_PRODUCT_ID => '123456789012',
            Product::PARAM_TITLE => 'Кроссовки лето',
            Product::PARAM_SHOP_ID => 1,
            Product::PARAM_USER_CREATED_ID => $this->user->id,
        ]);

        $this->assertDatabaseHas(ProductUserData::TABLE_NAME, [
            ProductUserData::PARAM_PRODUCT_ID => $product->id,
            ProductUserData::PARAM_USER_ID => $this->user->id,
            ProductUserData::PARAM_AVAILABLE => true,
        ]);
    }

    public function test_create_returns_product_with_same_products_loaded(): void
    {
        $book = Book::factory()->create();
        $candidate = $this->sourcedProduct($book);
        $this->price($candidate, 800);

        $product = $this->repo->create($this->dto([
            'book' => [Book::PARAM_ID => $book->id],
            'flags' => [ProductFlag::SavePrices->value => true],
            'prices' => [['price' => 1000, 'date' => '2026-01-01 10:00:00']],
        ]));

        $this->assertTrue($product->relationLoaded(Product::PARAM_SAME_PRODUCTS));
        $this->assertSame(
            [$candidate->id],
            $product->getSameProducts()->pluck(Product::PARAM_ID)->all(),
        );
    }

    public function test_create_without_save_prices_flag_skips_prices(): void
    {
        $product = $this->repo->create($this->dto([
            'prices' => [['price' => 500, 'date' => '2026-01-01 10:00:00']],
        ]));

        $this->assertDatabaseMissing(ProductPrice::TABLE_NAME, [
            ProductPrice::PARAM_PRODUCT_ID => $product->id,
        ]);
    }

    public function test_create_with_save_prices_flag_saves_prices(): void
    {
        $product = $this->repo->create($this->dto([
            'flags' => [ProductFlag::SavePrices->value => true],
            'prices' => [['price' => 500, 'date' => '2026-01-01 10:00:00']],
        ]));

        $this->assertDatabaseHas(ProductPrice::TABLE_NAME, [
            ProductPrice::PARAM_PRODUCT_ID => $product->id,
            ProductPrice::PARAM_USER_ID => $this->user->id,
            ProductPrice::PARAM_PRICE => 500,
        ]);
    }

    public function test_create_without_save_stocks_flag_skips_stocks(): void
    {
        $product = $this->repo->create($this->dto([
            'stocks' => [['qty' => 5, 'date' => '2026-01-01 10:00:00']],
        ]));

        $this->assertDatabaseMissing(ProductStock::TABLE_NAME, [
            ProductStock::PARAM_PRODUCT_ID => $product->id,
        ]);
    }

    public function test_create_with_save_stocks_flag_saves_stocks(): void
    {
        $product = $this->repo->create($this->dto([
            'flags' => [ProductFlag::SaveStocks->value => true],
            'stocks' => [['qty' => 5, 'date' => '2026-01-01 10:00:00']],
        ]));

        $this->assertDatabaseHas(ProductStock::TABLE_NAME, [
            ProductStock::PARAM_PRODUCT_ID => $product->id,
            ProductStock::PARAM_USER_ID => $this->user->id,
            ProductStock::PARAM_QTY => 5,
        ]);
    }

    public function test_update_without_save_product_user_data_flag_keeps_pud(): void
    {
        $product = $this->repo->create($this->dto([
            'productUserData' => [ProductUserData::PARAM_AVAILABLE => true],
        ]));

        $this->repo->update($product->id, $this->dto([
            'productUserData' => [ProductUserData::PARAM_AVAILABLE => false],
        ]));

        $this->assertDatabaseHas(ProductUserData::TABLE_NAME, [
            ProductUserData::PARAM_PRODUCT_ID => $product->id,
            ProductUserData::PARAM_USER_ID => $this->user->id,
            ProductUserData::PARAM_AVAILABLE => true,
        ]);
    }

    public function test_update_with_save_product_user_data_flag_updates_pud(): void
    {
        $product = $this->repo->create($this->dto([
            'productUserData' => [ProductUserData::PARAM_AVAILABLE => true],
        ]));

        $this->repo->update($product->id, $this->dto([
            'productUserData' => [ProductUserData::PARAM_AVAILABLE => false],
            'flags' => [ProductFlag::SaveProductUserData->value => true],
        ]));

        $this->assertDatabaseHas(ProductUserData::TABLE_NAME, [
            ProductUserData::PARAM_PRODUCT_ID => $product->id,
            ProductUserData::PARAM_USER_ID => $this->user->id,
            ProductUserData::PARAM_AVAILABLE => false,
        ]);
    }

    /**
     * Документация дефекта: гейт в savePrices() снова жив — `return`
     * раскомментирован, и при наличии у товара цен и ненулевом minPrice
     * ветка выхода срабатывает, когда последняя цена из DTO
     * ($latestPrice[PARAM_PRICE]) >= minPrice: новые цены не сохраняются
     * вовсе. Если `return` снова закомментируют (гейт станет мёртвым
     * кодом) — тест нужно перевернуть на «цены всегда upsert-ятся».
     */
    public function test_update_skips_prices_when_product_already_has_prices(): void
    {
        $product = $this->repo->create($this->dto([
            'flags' => [ProductFlag::SavePrices->value => true],
            'prices' => [['price' => 500, 'date' => '2026-01-01 10:00:00']],
        ]));

        $this->repo->update($product->id, $this->dto([
            'flags' => [ProductFlag::SavePrices->value => true],
            'prices' => [['price' => 600, 'date' => '2026-02-01 10:00:00']],
        ]));

        $this->assertDatabaseCount(ProductPrice::TABLE_NAME, 1);
        $this->assertDatabaseMissing(ProductPrice::TABLE_NAME, [
            ProductPrice::PARAM_PRODUCT_ID => $product->id,
            ProductPrice::PARAM_PRICE => 600,
        ]);
    }

    public function test_update_skips_stocks_when_last_stock_same_day_and_qty(): void
    {
        $product = $this->repo->create($this->dto([
            'flags' => [ProductFlag::SaveStocks->value => true],
            'stocks' => [['qty' => 5, 'date' => '2026-11-17 08:00:00']],
        ]));

        $this->repo->update($product->id, $this->dto([
            'flags' => [ProductFlag::SaveStocks->value => true],
            'stocks' => [['qty' => 5, 'date' => '2026-11-17 20:00:00']],
        ]));

        $this->assertDatabaseCount(ProductStock::TABLE_NAME, 1);
        $this->assertDatabaseMissing(ProductStock::TABLE_NAME, [
            ProductStock::PARAM_PRODUCT_ID => $product->id,
            ProductStock::PARAM_DATE => '2026-11-17 20:00:00',
        ]);
    }

    public function test_update_saves_stocks_when_qty_differs(): void
    {
        $product = $this->repo->create($this->dto([
            'flags' => [ProductFlag::SaveStocks->value => true],
            'stocks' => [['qty' => 5, 'date' => '2026-11-17 08:00:00']],
        ]));

        $this->repo->update($product->id, $this->dto([
            'flags' => [ProductFlag::SaveStocks->value => true],
            'stocks' => [['qty' => 3, 'date' => '2026-11-17 20:00:00']],
        ]));

        $this->assertDatabaseCount(ProductStock::TABLE_NAME, 2);
        $this->assertDatabaseHas(ProductStock::TABLE_NAME, [
            ProductStock::PARAM_PRODUCT_ID => $product->id,
            ProductStock::PARAM_USER_ID => $this->user->id,
            ProductStock::PARAM_QTY => 3,
        ]);
    }

    public function test_update_returns_product_with_same_products_loaded(): void
    {
        $book = Book::factory()->create();
        $candidate = $this->sourcedProduct($book);
        $this->price($candidate, 800);

        $parent = $this->repo->create($this->dto(['book' => [Book::PARAM_ID => $book->id]]));

        $product = $this->repo->update($parent->id, $this->dto(['book' => [Book::PARAM_ID => $book->id]]));

        $this->assertTrue($product->relationLoaded(Product::PARAM_SAME_PRODUCTS));
        $this->assertSame(
            [$candidate->id],
            $product->getSameProducts()->pluck(Product::PARAM_ID)->all(),
        );
    }

    public function test_get_scopes_by_session_shop_and_loads_same_products(): void
    {
        $book = Book::factory()->create();

        $parent = $this->repo->create($this->dto(['book' => [Book::PARAM_ID => $book->id]]));
        $sameShop = $this->sourcedProduct($book);
        $foreignShop = $this->sourcedProduct($book, [Product::PARAM_SHOP_ID => 2]);

        $this->price($sameShop, 200);
        $this->price($foreignShop, 50);

        $product = $this->repo->get($parent->id);

        $this->assertTrue($product->relationLoaded(Product::PARAM_SAME_PRODUCTS));

        // лоадер не скоупит кандидатов по магазину: под указанную сессию
        // попадают кандидаты и из shop-2
        $this->assertEqualsCanonicalizing(
            [$sameShop->id, $foreignShop->id],
            $product->getSameProducts()->pluck(Product::PARAM_ID)->all(),
        );

        $this->expectException(ModelNotFoundException::class);

        $this->repo->get($foreignShop->id);
    }

    public function test_get_all_scopes_by_session_shop_and_loads_same_products(): void
    {
        $book = Book::factory()->create();

        $first = $this->sourcedProduct($book);
        $second = $this->sourcedProduct($book);
        $foreign = Product::factory()->create([Product::PARAM_SHOP_ID => 2]);

        $this->price($first, 1000);
        $this->price($second, 800);

        $products = $this->repo->getAll(new GetProductsQuery(new CollectionQueryDto));

        $this->assertEqualsCanonicalizing(
            [$first->id, $second->id],
            $products->pluck(Product::PARAM_ID)->all(),
        );

        // лоадер вешает связь на инстансы из выборки репозитория,
        // а не на фабричные переменные — берём товар из $products
        $firstLoaded = $products->firstWhere(Product::PARAM_ID, $first->id);

        $this->assertTrue($firstLoaded->relationLoaded(Product::PARAM_SAME_PRODUCTS));
        $this->assertSame(
            [$second->id],
            $firstLoaded->getSameProducts()->pluck(Product::PARAM_ID)->all(),
        );
    }

    public function test_get_all_paginate_returns_paginator_with_same_products_loaded(): void
    {
        $book = Book::factory()->create();

        $first = $this->sourcedProduct($book);
        $second = $this->sourcedProduct($book);

        $this->price($first, 1000);
        $this->price($second, 800);

        $paginator = $this->repo->getAllPaginate(new GetProductsPaginateQuery(new CollectionQueryDto));

        $this->assertInstanceOf(LengthAwarePaginator::class, $paginator);
        $this->assertSame(2, $paginator->total());
        $this->assertSame(2, $paginator->count());
        $this->assertSame(15, $paginator->perPage());

        foreach ($paginator->getCollection() as $product) {
            $this->assertTrue($product->relationLoaded(Product::PARAM_SAME_PRODUCTS));
        }
    }
}
