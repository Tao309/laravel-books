<?php

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\Authors\StoreAuthorRequest;
use App\Http\Requests\Authors\UpdateAuthorRequest;
use App\Http\Requests\BindingTypes\StoreBindingTypeRequest;
use App\Http\Requests\BindingTypes\UpdateBindingTypeRequest;
use App\Http\Requests\Books\StoreBookRequest;
use App\Http\Requests\Books\UpdateBookRequest;
use App\Http\Requests\BookSeries\StoreBookSeriesRequest;
use App\Http\Requests\BookSeries\UpdateBookSeriesRequest;
use App\Http\Requests\Cities\StoreCityRequest;
use App\Http\Requests\Cities\UpdateCityRequest;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\PaperTypes\StorePaperTypeRequest;
use App\Http\Requests\PaperTypes\UpdatePaperTypeRequest;
use App\Http\Requests\Products\ArchiveProductRequest;
use App\Http\Requests\Products\MassSaveProductRequest;
use App\Http\Requests\Products\StoreProductRequest;
use App\Http\Requests\Products\UpdateProductRequest;
use App\Http\Requests\PublishingBrands\StorePublishingBrandRequest;
use App\Http\Requests\PublishingBrands\UpdatePublishingBrandRequest;
use App\Http\Requests\PublishingHouses\StorePublishingHouseRequest;
use App\Http\Requests\PublishingHouses\UpdatePublishingHouseRequest;
use App\Http\Requests\SourceProducts\StoreSourceProductRequest;
use App\Http\Requests\SourceProducts\UpdateSourceProductRequest;
use App\Http\Requests\SourceProductTypes\StoreSourceProductTypeRequest;
use App\Http\Requests\SourceProductTypes\UpdateSourceProductTypeRequest;
use App\Models\Author;
use App\Models\BindingType;
use App\Models\Book;
use App\Models\BookSeries;
use App\Models\BookUserData;
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
use App\Models\SourceProductUserData;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class RequestContractsTest extends TestCase
{
    public static function requestProvider(): array
    {
        $bookCommon = [
            Book::PARAM_PAGES,
            Book::PARAM_CIRCULATION,
            Book::PARAM_SIZE,
            Book::PARAM_PUBLISH_YEAR,
            Book::PARAM_AUTHORS,
            Book::PARAM_AUTHORS . '.*.' . Author::PARAM_ID,
            Book::PARAM_BINDING_TYPE,
            Book::PARAM_BINDING_TYPE . '.' . BindingType::PARAM_ID,
            Book::PARAM_PAPER_TYPE . '.' . PaperType::PARAM_ID,
            Book::PARAM_PUBLISHING_HOUSE . '.' . PublishingHouse::PARAM_ID,
            Book::PARAM_PUBLISHING_BRAND . '.' . PublishingBrand::PARAM_ID,
            Book::PARAM_BOOK_SERIES . '.' . BookSeries::PARAM_ID,
            Book::PARAM_BOOK_USER_DATA . '.' . BookUserData::PARAM_RELEASE_DATE,
        ];

        $productCommon = [
            Product::PARAM_SHOP_PRODUCT_CODE,
            Product::PARAM_TITLE,
            Product::PARAM_CITY . '.' . City::PARAM_ID,
            Product::PARAM_BOOK . '.' . Book::PARAM_ID,
            Product::PARAM_SOURCE_PRODUCT . '.' . SourceProduct::PARAM_ID,
            Product::PARAM_PRODUCT_USER_DATA . '.' . ProductUserData::PARAM_AVAILABLE,
            Product::PARAM_PRODUCT_USER_DATA . '.' . ProductUserData::PARAM_IS_ARCHIVE,
            Product::PARAM_PRICES,
            Product::PARAM_PRICES . '.*.' . ProductPrice::PARAM_PRICE,
            Product::PARAM_PRICES . '.*.' . ProductPrice::PARAM_DATE,
            Product::PARAM_STOCKS,
            Product::PARAM_STOCKS . '.*.' . ProductStock::PARAM_QTY,
            Product::PARAM_STOCKS . '.*.' . ProductStock::PARAM_DATE,
            Product::PARAM_STOCKS . '.*.' . ProductStock::PARAM_LOG,
        ];

        $sourceProductCommon = [
            SourceProduct::PARAM_TITLE,
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE . '.' . SourceProductType::PARAM_ID,
            SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA . '.' . SourceProductUserData::PARAM_LISTEN_PRICE_VALUE,
            SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA . '.' . SourceProductUserData::PARAM_COMMENT,
        ];

        return [
            'store author' => [StoreAuthorRequest::class, [Author::PARAM_FIRST_NAME, Author::PARAM_LAST_NAME]],
            'update author' => [UpdateAuthorRequest::class, [Author::PARAM_FIRST_NAME, Author::PARAM_LAST_NAME]],
            'store binding type' => [StoreBindingTypeRequest::class, [BindingType::PARAM_LABEL]],
            'update binding type' => [UpdateBindingTypeRequest::class, [BindingType::PARAM_LABEL]],
            'store book' => [StoreBookRequest::class, array_merge([
                Book::PARAM_TITLE,
                Book::PARAM_ORIGINAL_TITLE,
                Book::PARAM_ISBN,
            ], $bookCommon)],
            'update book' => [UpdateBookRequest::class, array_merge([
                Book::PARAM_TITLE,
                Book::PARAM_ORIGINAL_TITLE,
                Book::PARAM_ISBN,
            ], $bookCommon)],
            'store book series' => [StoreBookSeriesRequest::class, [BookSeries::PARAM_NAME]],
            'update book series' => [UpdateBookSeriesRequest::class, [BookSeries::PARAM_NAME]],
            'store city' => [StoreCityRequest::class, [City::PARAM_CODE, City::PARAM_TITLE]],
            'update city' => [UpdateCityRequest::class, [City::PARAM_CODE, City::PARAM_TITLE]],
            'store paper type' => [StorePaperTypeRequest::class, [PaperType::PARAM_LABEL, PaperType::PARAM_DESCRIPTION]],
            'update paper type' => [UpdatePaperTypeRequest::class, [PaperType::PARAM_LABEL, PaperType::PARAM_DESCRIPTION]],
            'store publishing brand' => [StorePublishingBrandRequest::class, [PublishingBrand::PARAM_NAME]],
            'update publishing brand' => [UpdatePublishingBrandRequest::class, [PublishingBrand::PARAM_NAME]],
            'store publishing house' => [StorePublishingHouseRequest::class, [PublishingHouse::PARAM_NAME]],
            'update publishing house' => [UpdatePublishingHouseRequest::class, [PublishingHouse::PARAM_NAME]],
            'store source product' => [StoreSourceProductRequest::class, $sourceProductCommon],
            'update source product' => [UpdateSourceProductRequest::class, $sourceProductCommon],
            'store source product type' => [StoreSourceProductTypeRequest::class, [SourceProductType::PARAM_CODE, SourceProductType::PARAM_NAME]],
            'update source product type' => [UpdateSourceProductTypeRequest::class, [SourceProductType::PARAM_CODE, SourceProductType::PARAM_NAME]],
            'store product' => [StoreProductRequest::class, array_merge([
                Product::PARAM_SHOP_PRODUCT_ID,
                Product::PARAM_SHOP,
                Product::PARAM_SHOP . '.' . Shop::PARAM_ID,
            ], $productCommon)],
            'update product' => [UpdateProductRequest::class, array_merge([
                Product::PARAM_SHOP_PRODUCT_ID,
                Product::PARAM_SHOP,
                Product::PARAM_SHOP . '.' . Shop::PARAM_ID,
            ], $productCommon)],
            'archive product' => [ArchiveProductRequest::class, [
                Product::PARAM_SHOP_PRODUCT_ID,
                ProductUserData::PARAM_IS_ARCHIVE,
            ]],
            'mass save product' => [MassSaveProductRequest::class, ['items']],
            'index' => [IndexRequest::class, [
                IndexRequest::FIELD_SEARCH,
                IndexRequest::FIELD_IDS,
                'id',
                'id.*',
                IndexRequest::FIELD_LIMIT,
            ]],
        ];
    }

    #[DataProvider('requestProvider')]
    public function test_request_authorizes_and_declares_expected_rules(string $requestClass, array $expectedKeys): void
    {
        $request = new $requestClass;

        $this->assertTrue($request->authorize());

        $rules = $request->rules();
        $this->assertIsArray($rules);

        foreach ($expectedKeys as $key) {
            $this->assertArrayHasKey($key, $rules, "Missing {$key} in {$requestClass}");
            $this->assertIsArray($rules[$key], "Rule for {$key} in {$requestClass} should be an array");
        }
    }

    public function test_index_request_constants_and_messages_use_request_rule_names(): void
    {
        $request = new IndexRequest;

        $this->assertSame([
            IndexRequest::FIELD_IDS . '.string',
            IndexRequest::FIELD_IDS . '.regex',
            IndexRequest::FIELD_SEARCH . '.required',
            IndexRequest::FIELD_SEARCH . '.min',
            IndexRequest::FIELD_SEARCH . '.max',
        ], array_keys($request->messages()));
    }

    public function test_mass_save_rules_prefix_item_rules_and_keep_explicit_identifiers(): void
    {
        $request = new MassSaveProductRequest;
        $request->merge(['items' => [
            [
                Product::PARAM_SHOP_PRODUCT_ID => '123456789012',
                Product::PARAM_SHOP => [Shop::PARAM_ID => 1],
                Product::PARAM_TITLE => 'Valid product title',
                Product::PARAM_PRICES => [],
                Product::PARAM_STOCKS => [],
            ],
            [
                Product::PARAM_ID => 42,
                Product::PARAM_SHOP_PRODUCT_ID => '123456789013',
                Product::PARAM_SHOP => [Shop::PARAM_ID => 1],
                Product::PARAM_TITLE => 'Another product title',
                Product::PARAM_PRICES => [],
                Product::PARAM_STOCKS => [],
            ],
        ]]);

        $rules = $request->rules();

        $this->assertArrayHasKey('items', $rules);
        $this->assertArrayHasKey('items.0.title', $rules);
        $this->assertArrayHasKey('items.1.title', $rules);
        $this->assertArrayHasKey('items.0.' . Product::PARAM_SHOP_PRODUCT_ID, $rules);
        $this->assertArrayHasKey('items.1.' . Product::PARAM_ID, $rules);
        $this->assertContains('required_without:items.0.' . Product::PARAM_SHOP_PRODUCT_ID, $rules['items.0.' . Product::PARAM_ID]);
    }
}
