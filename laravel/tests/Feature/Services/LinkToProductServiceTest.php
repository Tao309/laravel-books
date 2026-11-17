<?php

namespace Tests\Feature\Services;

use App\Exceptions\ApiException;
use App\Models\Book;
use App\Models\BookUserData;
use App\Models\Product;
use App\Models\Shop;
use App\Models\SourceProduct;
use App\Models\SourceProductUserData;
use App\Models\User;
use App\Services\LinkToProductService;
use App\Services\SessionShopService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * LinkToProductService: linkBook/linkSourceProduct — конфликт по уже
 * привязанному source, 404-обёртка в ApiException, создание BUD/SPUD
 * только когда их нет у текущего пользователя.
 */
class LinkToProductServiceTest extends TestCase
{
    use RefreshDatabase;

    private LinkToProductService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $shop = Shop::factory()->ozon()->create();

        $this->actingAs(User::factory()->create());

        $this->app->make(SessionShopService::class)->setShop($shop);

        $this->service = $this->app->make(LinkToProductService::class);
    }

    /**
     * Источник у товара не задан: фабрика гнездит случайный source —
     * гасим через явные null (как в ProductActionsApiTest).
     */
    private function product(): Product
    {
        return Product::factory()->state(fn () => [
            Product::PARAM_SOURCE_TYPE => null,
            Product::PARAM_SOURCE_ID => null,
        ])->create([Product::PARAM_SHOP_ID => 1]);
    }

    public function test_link_book_associates_and_persists_source(): void
    {
        $product = $this->product();
        $book = Book::factory()->create();

        $result = $this->service->linkBook($product->id, $book->id);

        $this->assertSame($book->id, $result->source->id);
        $this->assertDatabaseHas(Product::TABLE_NAME, [
            Product::PARAM_ID => $product->id,
            Product::PARAM_SOURCE_TYPE => Book::class,
            Product::PARAM_SOURCE_ID => $book->id,
        ]);
    }

    public function test_link_book_creates_missing_book_user_data(): void
    {
        $product = $this->product();
        $book = Book::factory()->create();

        // фабрика уже создала BUD владельцу — убираем, чтобы попасть
        // в ветку «нет BUD»
        BookUserData::query()
            ->where(BookUserData::PARAM_BOOK_ID, $book->id)
            ->delete();

        $this->service->linkBook($product->id, $book->id);

        $this->assertDatabaseCount(BookUserData::TABLE_NAME, 1);
        $this->assertDatabaseHas(BookUserData::TABLE_NAME, [
            BookUserData::PARAM_BOOK_ID => $book->id,
            BookUserData::PARAM_USER_ID => \Auth::id(),
        ]);
    }

    public function test_link_book_keeps_existing_book_user_data(): void
    {
        $product = $this->product();
        // Book::factory() afterCreating создаёт BUD для текущего пользователя
        $book = Book::factory()->create();

        $this->service->linkBook($product->id, $book->id);

        $this->assertDatabaseCount(BookUserData::TABLE_NAME, 1);
    }

    public function test_link_book_conflict_when_product_already_sourced(): void
    {
        // forBook() вешает случайную книгу — источник уже привязан
        $product = Product::factory()->forBook()->create([Product::PARAM_SHOP_ID => 1]);
        $book = Book::factory()->create();

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Product already has Book or SourceProduct');

        $this->service->linkBook($product->id, $book->id);
    }

    public function test_link_book_missing_book_wrapped_in_api_exception(): void
    {
        $product = $this->product();

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Not found Book by id = 99999');

        $this->service->linkBook($product->id, 99999);
    }

    public function test_link_source_product_associates_and_persists_source(): void
    {
        $product = $this->product();
        $sourceProduct = SourceProduct::factory()->create();

        $result = $this->service->linkSourceProduct($product->id, $sourceProduct->id);

        $this->assertSame($sourceProduct->id, $result->source->id);
        $this->assertDatabaseHas(Product::TABLE_NAME, [
            Product::PARAM_ID => $product->id,
            Product::PARAM_SOURCE_TYPE => SourceProduct::class,
            Product::PARAM_SOURCE_ID => $sourceProduct->id,
        ]);
    }

    public function test_link_source_product_creates_missing_spud(): void
    {
        $product = $this->product();
        $sourceProduct = SourceProduct::factory()->create();

        SourceProductUserData::query()
            ->where(SourceProductUserData::PARAM_SOURCE_PRODUCT_ID, $sourceProduct->id)
            ->delete();

        $this->service->linkSourceProduct($product->id, $sourceProduct->id);

        $this->assertDatabaseCount(SourceProductUserData::TABLE_NAME, 1);
        $this->assertDatabaseHas(SourceProductUserData::TABLE_NAME, [
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $sourceProduct->id,
            SourceProductUserData::PARAM_USER_ID => \Auth::id(),
        ]);
    }

    public function test_link_source_product_keeps_existing_spud(): void
    {
        $product = $this->product();
        // SourceProduct::factory() afterCreating создаёт SPUD для текущего пользователя
        $sourceProduct = SourceProduct::factory()->create();

        $this->service->linkSourceProduct($product->id, $sourceProduct->id);

        $this->assertDatabaseCount(SourceProductUserData::TABLE_NAME, 1);
    }

    public function test_link_source_product_conflict_when_already_sourced(): void
    {
        $product = Product::factory()->forSourceProduct()->create([Product::PARAM_SHOP_ID => 1]);
        $sourceProduct = SourceProduct::factory()->create();

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Product already has Book or SourceProduct');

        $this->service->linkSourceProduct($product->id, $sourceProduct->id);
    }

    public function test_link_source_product_missing_wrapped_in_api_exception(): void
    {
        $product = $this->product();

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Not found SourceProduct by id = 99999');

        $this->service->linkSourceProduct($product->id, 99999);
    }
}
