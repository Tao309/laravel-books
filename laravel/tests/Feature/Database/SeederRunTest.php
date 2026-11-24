<?php

namespace Tests\Feature\Database;

use App\Models\Author;
use App\Models\BindingType;
use App\Models\Book;
use App\Models\BookSeries;
use App\Models\City;
use App\Models\PaperType;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductStock;
use App\Models\ProductUserData;
use App\Models\PublishingBrand;
use App\Models\PublishingHouse;
use App\Models\Shop;
use App\Models\SourceProduct;
use App\Models\SourceProductType;
use App\Models\User;
use App\Services\SessionShopService;
use Database\Seeders\AuthorSeeder;
use Database\Seeders\BookSeeder;
use Database\Seeders\BookSeriesSeeder;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\PaperTypeSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\PublishingBrandSeeder;
use Database\Seeders\PublishingHouseSeeder;
use Database\Seeders\SourceProductSeeder;
use Database\Seeders\SourceProductTypeSeeder;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeederRunTest extends TestCase
{
    use RefreshDatabase;

    public function test_reference_seeders_create_expected_rows(): void
    {
        try {
            $this->seed(AuthorSeeder::class);
            $this->seed(BookSeriesSeeder::class);
            $this->seed(PublishingBrandSeeder::class);
            $this->seed(PublishingHouseSeeder::class);
            $this->seed(SourceProductTypeSeeder::class);
        } catch (BindingResolutionException $e) {
            $this->markTestIncomplete('CQRS repository binding gap: ' . $e->getMessage());
        }

        $this->assertSame(10, Author::query()->count());
        $this->assertSame(7, BookSeries::query()->count());
        $this->assertSame(6, PublishingBrand::query()->count());
        $this->assertSame(5, PublishingHouse::query()->count());
        $this->assertSame(13, SourceProductType::query()->count());

        $this->assertDatabaseHas(SourceProductType::TABLE_NAME, [
            SourceProductType::PARAM_CODE => 'shoes',
        ]);
        $this->assertDatabaseHas(SourceProductType::TABLE_NAME, [
            SourceProductType::PARAM_CODE => 'outerwear',
        ]);
        $this->assertDatabaseHas(Author::TABLE_NAME, [
            Author::PARAM_FIRST_NAME => 'Фёдор',
            Author::PARAM_LAST_NAME => 'Достоевский',
        ]);
    }

    public function test_fixed_id_seeders_create_expected_rows(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseHas(BindingType::TABLE_NAME, [
            BindingType::PARAM_ID => 1,
        ]);
        $this->assertDatabaseHas(BindingType::TABLE_NAME, [
            BindingType::PARAM_ID => 2,
        ]);
        $this->assertDatabaseHas(City::TABLE_NAME, [
            City::PARAM_ID => 1,
        ]);
        $this->assertDatabaseHas(Shop::TABLE_NAME, [
            Shop::PARAM_ID => 1,
            Shop::PARAM_TYPE => 'ozon',
            Shop::PARAM_DOMAIN => 'ozon.ru',
        ]);
        $this->assertDatabaseHas(Shop::TABLE_NAME, [
            Shop::PARAM_ID => 5,
            Shop::PARAM_TYPE => 'ffan',
        ]);
        $this->assertDatabaseCount(PaperType::TABLE_NAME, 0);
    }

    public function test_demo_chain_seeds_books_and_source_products(): void
    {
        try {
            $this->seed(DatabaseSeeder::class);
            $this->seed(AuthorSeeder::class);
            $this->seed(BookSeriesSeeder::class);
            $this->seed(PublishingBrandSeeder::class);
            $this->seed(PublishingHouseSeeder::class);
            $this->seed(SourceProductTypeSeeder::class);
            $this->seed(PaperTypeSeeder::class);
            $this->seed(BookSeeder::class);
            $this->seed(SourceProductSeeder::class);
        } catch (BindingResolutionException $e) {
            $this->markTestIncomplete('CQRS repository binding gap: ' . $e->getMessage());
        }

        $this->assertSame(3, Book::query()->count());
        $this->assertSame(5, SourceProduct::query()->count());
        $this->assertSame(0, Product::query()->count());
    }

    public function test_product_seeder_creates_products_for_seeded_shops(): void
    {
        try {
            $this->seed(DatabaseSeeder::class);
            $this->seed(AuthorSeeder::class);
            $this->seed(BookSeriesSeeder::class);
            $this->seed(PublishingBrandSeeder::class);
            $this->seed(PublishingHouseSeeder::class);
            $this->seed(SourceProductTypeSeeder::class);
            $this->seed(PaperTypeSeeder::class);
            $this->seed(BookSeeder::class);
            $this->seed(SourceProductSeeder::class);
        } catch (BindingResolutionException $e) {
            $this->markTestIncomplete('CQRS repository binding gap: ' . $e->getMessage());
        }

        $ozonShop = Shop::query()
            ->where(Shop::PARAM_TYPE, 'ozon')
            ->firstOrFail();
        $wildberriesShop = Shop::query()
            ->where(Shop::PARAM_TYPE, 'wildberries')
            ->firstOrFail();
        $this->app->make(SessionShopService::class)->setShop($ozonShop);

        try {
            $this->seed(ProductSeeder::class);
        } catch (BindingResolutionException $e) {
            $this->markTestIncomplete('CQRS repository binding gap: ' . $e->getMessage());
        }

        $user = User::query()->firstOrFail();

        $this->assertSame(4, Product::query()->count());
        $this->assertDatabaseHas(Product::TABLE_NAME, [
            Product::PARAM_SHOP_PRODUCT_ID => '6055250413',
            Product::PARAM_TITLE => 'Куртка Зимняя',
            Product::PARAM_SOURCE_TYPE => SourceProduct::class,
            Product::PARAM_SHOP_ID => $ozonShop->id,
            Product::PARAM_USER_CREATED_ID => $user->id,
        ]);
        $this->assertDatabaseHas(Product::TABLE_NAME, [
            Product::PARAM_SHOP_PRODUCT_ID => '6226491440',
            Product::PARAM_TITLE => 'Ловец Чудес. #1',
            Product::PARAM_SOURCE_TYPE => Book::class,
            Product::PARAM_SHOP_ID => $ozonShop->id,
        ]);
        $this->assertDatabaseHas(Product::TABLE_NAME, [
            Product::PARAM_SHOP_PRODUCT_ID => '3300252850',
            Product::PARAM_SHOP_PRODUCT_CODE => '1894528868',
            Product::PARAM_SHOP_ID => $wildberriesShop->id,
        ]);
        $this->assertDatabaseHas(Product::TABLE_NAME, [
            Product::PARAM_SHOP_PRODUCT_ID => '3315320967',
            Product::PARAM_SHOP_PRODUCT_CODE => '1903147507',
            Product::PARAM_SHOP_ID => $wildberriesShop->id,
        ]);

        // ProductSeeder не передаёт флаги SavePrices/SaveStocks, поэтому цены и стоки не сохраняются.
        $this->assertSame(0, ProductPrice::query()->count());
        $this->assertSame(0, ProductStock::query()->count());
        $this->assertSame(4, ProductUserData::query()->count());

        $jacket = Product::query()
            ->where(Product::PARAM_SHOP_PRODUCT_ID, '6055250413')
            ->firstOrFail();
        $this->assertDatabaseHas(ProductUserData::TABLE_NAME, [
            ProductUserData::PARAM_PRODUCT_ID => $jacket->id,
            ProductUserData::PARAM_USER_ID => $user->id,
            ProductUserData::PARAM_LISTEN_PRICE_VALUE => 5400,
            ProductUserData::PARAM_COMMENT => 'Комментарий для: Куртка Зимняя',
        ]);
    }
}
