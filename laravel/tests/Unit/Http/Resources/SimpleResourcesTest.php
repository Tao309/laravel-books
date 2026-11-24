<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\Authors\AuthorResource;
use App\Http\Resources\BindingTypes\BindingTypeResource;
use App\Http\Resources\BookSeries\BookSeriesResource;
use App\Http\Resources\BookUserDatas\BookUserDataResource;
use App\Http\Resources\Cities\CityResource;
use App\Http\Resources\PaperTypes\PaperTypeResource;
use App\Http\Resources\ProductPrices\ProductPriceResource;
use App\Http\Resources\ProductStocks\ProductStockResource;
use App\Http\Resources\ProductUserDatas\ProductUserDataResource;
use App\Http\Resources\PublishingBrand\PublishingBrandResource;
use App\Http\Resources\PublishingHouses\PublishingHouseResource;
use App\Http\Resources\Shops\ShopResource;
use App\Http\Resources\SourceProductTypes\SourceProductTypeResource;
use App\Http\Resources\SourceProductUserDatas\SourceProductUserDataResource;
use App\Models\Author;
use App\Models\BindingType;
use App\Models\BookSeries;
use App\Models\BookUserData;
use App\Models\City;
use App\Models\PaperType;
use App\Models\ProductPrice;
use App\Models\ProductStock;
use App\Models\ProductUserData;
use App\Models\PublishingBrand;
use App\Models\PublishingHouse;
use App\Models\Shop;
use App\Models\SourceProductType;
use App\Models\SourceProductUserData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SimpleResourcesTest extends TestCase
{
    public static function resourceProvider(): array
    {
        return [
            'author' => [AuthorResource::class, Author::class, [
                'id', 'first_name', 'last_name', Model::UPDATED_AT, Model::CREATED_AT,
            ], [
                Author::PARAM_FIRST_NAME => 'Ada',
                Author::PARAM_LAST_NAME => 'Lovelace',
            ]],
            'binding type' => [BindingTypeResource::class, BindingType::class, [
                'id', 'label', Model::UPDATED_AT, Model::CREATED_AT,
            ], [BindingType::PARAM_LABEL => 'Resource binding']],
            'book series' => [BookSeriesResource::class, BookSeries::class, [
                'id', 'name', Model::UPDATED_AT, Model::CREATED_AT,
            ], [BookSeries::PARAM_NAME => 'Resource series']],
            'book user data' => [BookUserDataResource::class, BookUserData::class, [
                'release_date', 'listen_price_value', 'comment', 'user_id', 'book_id',
                Model::UPDATED_AT, Model::CREATED_AT,
            ], [
                BookUserData::PARAM_BOOK_ID => 1,
                BookUserData::PARAM_USER_ID => 1,
            ]],
            'city' => [CityResource::class, City::class, [
                'id', 'code', 'title', Model::UPDATED_AT, Model::CREATED_AT,
            ], [
                City::PARAM_CODE => 'RES',
                City::PARAM_TITLE => 'Resource city',
            ]],
            'paper type' => [PaperTypeResource::class, PaperType::class, [
                'id', 'label', 'description', Model::UPDATED_AT, Model::CREATED_AT,
            ], [PaperType::PARAM_LABEL => 'Resource paper']],
            'product price' => [ProductPriceResource::class, ProductPrice::class, ['price', 'date'], [
                ProductPrice::PARAM_PRODUCT_ID => 1,
                ProductPrice::PARAM_USER_ID => 1,
            ]],
            'product stock' => [ProductStockResource::class, ProductStock::class, ['qty', 'date', 'log'], [
                ProductStock::PARAM_PRODUCT_ID => 1,
                ProductStock::PARAM_USER_ID => 1,
            ]],
            'product user data' => [ProductUserDataResource::class, ProductUserData::class, [
                'available',
                'not_available_date_from',
                'available_date_from',
                'listen_price_value',
                'listen_qty_value',
                'release_date',
                'is_archive',
                'comment',
                'product_id',
                'user_id',
                Model::UPDATED_AT,
                Model::CREATED_AT,
            ], [
                ProductUserData::PARAM_PRODUCT_ID => 1,
                ProductUserData::PARAM_USER_ID => 1,
            ]],
            'publishing brand' => [PublishingBrandResource::class, PublishingBrand::class, [
                'id', 'name', Model::UPDATED_AT, Model::CREATED_AT,
            ], [PublishingBrand::PARAM_NAME => 'Resource brand']],
            'publishing house' => [PublishingHouseResource::class, PublishingHouse::class, [
                'id', 'name', Model::UPDATED_AT, Model::CREATED_AT,
            ], [PublishingHouse::PARAM_NAME => 'Resource house']],
            'shop' => [ShopResource::class, Shop::class, [
                'id', 'type', 'domain', 'url', Model::UPDATED_AT, Model::CREATED_AT,
            ], [
                Shop::PARAM_TYPE => 'resource-shop',
                Shop::PARAM_DOMAIN => 'resource.test',
            ]],
            'source product type' => [SourceProductTypeResource::class, SourceProductType::class, [
                'id', 'code', 'name', Model::UPDATED_AT, Model::CREATED_AT,
            ], [
                SourceProductType::PARAM_CODE => 'resource-type',
                SourceProductType::PARAM_NAME => 'Resource type',
            ]],
            'source product user data' => [SourceProductUserDataResource::class, SourceProductUserData::class, [
                'listen_price_value', 'comment', 'user_id', 'source_product_id',
                Model::UPDATED_AT, Model::CREATED_AT,
            ], [
                SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => 1,
                SourceProductUserData::PARAM_USER_ID => 1,
            ]],
        ];
    }

    #[DataProvider('resourceProvider')]
    public function test_simple_resources_expose_current_contract_keys(
        string $resourceClass,
        string $modelClass,
        array $expectedKeys,
        array $attributes,
    ): void {
        $model = $modelClass::factory()->make($attributes);

        $data = (new $resourceClass($model))->resolve(new Request);

        $this->assertSame($expectedKeys, array_keys($data));
    }

    public function test_product_price_and_stock_resources_do_not_expose_identifiers(): void
    {
        $price = (new ProductPriceResource(ProductPrice::factory()->make([
            ProductPrice::PARAM_PRODUCT_ID => 1,
            ProductPrice::PARAM_USER_ID => 1,
        ])))->resolve(new Request);
        $stock = (new ProductStockResource(ProductStock::factory()->make([
            ProductStock::PARAM_PRODUCT_ID => 1,
            ProductStock::PARAM_USER_ID => 1,
        ])))->resolve(new Request);

        $this->assertArrayNotHasKey('id', $price);
        $this->assertArrayNotHasKey('id', $stock);
        $this->assertArrayNotHasKey(Model::CREATED_AT, $price);
        $this->assertArrayNotHasKey(Model::CREATED_AT, $stock);
    }
}
