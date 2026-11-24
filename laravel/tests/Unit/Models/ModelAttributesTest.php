<?php

namespace Tests\Unit\Models;

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
use App\Models\User;
use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionMethod;
use Tests\TestCase;

class ModelAttributesTest extends TestCase
{
    public static function modelProvider(): array
    {
        return [
            'author' => [Author::class, 'authors', ['first_name', 'last_name'], ['pivot'], []],
            'binding type' => [BindingType::class, 'binding_types', ['label'], [], []],
            'book' => [Book::class, 'books', [
                'title', 'original_title', 'isbn', 'pages', 'circulation', 'size', 'publish_year',
                'livelib_id', 'goodreads_id', 'fantlab_id', 'livelib_rating', 'goodreads_rating',
                'on_sale_count', 'binding_type_id', 'paper_type_id', 'publishing_house_id',
                'publishing_brand_id', 'book_series_id', 'user_created_id',
            ], ['binding_type_id', 'publishing_house_id', 'publishing_brand_id', 'book_series_id', 'user_created_id'], []],
            'book series' => [BookSeries::class, 'book_series', ['id', 'name'], [], []],
            'book user data' => [BookUserData::class, 'book_user_data', [
                'book_id', 'user_id', 'release_date', 'listen_price_value', 'comment',
            ], [], ['release_date' => 'datetime']],
            'city' => [City::class, 'cities', ['code', 'title'], [], []],
            'paper type' => [PaperType::class, 'paper_types', ['label', 'description'], [], []],
            'product' => [Product::class, 'products', [
                'shop_product_id', 'shop_product_code', 'title', 'shop_id', 'city_id',
                'user_created_id', 'source_type', 'source_id',
            ], ['shop_id', 'city_id', 'user_created_id'], ['min_price' => 'integer', 'last_qty' => 'integer']],
            'product price' => [ProductPrice::class, 'product_prices', [
                'product_id', 'user_id', 'price', 'date',
            ], ['product_id', 'user_id'], ['date' => 'datetime']],
            'product stock' => [ProductStock::class, 'product_stocks', [
                'product_id', 'user_id', 'qty', 'date', 'log',
            ], ['product_id', 'user_id'], ['date' => 'datetime', 'log' => 'array']],
            'product user data' => [ProductUserData::class, 'product_user_data', [
                'product_id', 'user_id', 'available', 'not_available_date_from',
                'available_date_from', 'listen_price_value', 'listen_qty_value',
                'release_date', 'is_archive', 'comment',
            ], ['product_id', 'user_id'], [
                'not_available_date_from' => 'datetime',
                'available_date_from' => 'datetime',
                'release_date' => 'datetime',
            ]],
            'publishing brand' => [PublishingBrand::class, 'publishing_brands', ['name'], [], []],
            'publishing house' => [PublishingHouse::class, 'publishing_houses', ['name'], [], []],
            'shop' => [Shop::class, 'shops', ['type', 'domain', 'url'], [], []],
            'source product' => [SourceProduct::class, 'source_products', [
                'title', 'source_product_type_id', 'user_created_id', 'on_sale_count',
            ], ['source_product_type_id', 'user_created_id'], []],
            'source product type' => [SourceProductType::class, 'source_product_types', ['code', 'name'], [], []],
            'source product user data' => [SourceProductUserData::class, 'source_product_user_data', [
                'source_product_id', 'user_id', 'listen_price_value', 'comment',
            ], [], []],
            'user' => [User::class, 'users', ['name', 'email', 'password'], [
                'password', 'remember_token', 'email_verified_at',
            ], ['email_verified_at' => 'datetime', 'password' => 'hashed']],
        ];
    }

    #[DataProvider('modelProvider')]
    public function test_model_attributes_match_current_contract(
        string $modelClass,
        string $table,
        array $fillable,
        array $hidden,
        array $casts,
    ): void {
        $model = new $modelClass;

        $this->assertSame($table, $model->getTable());
        $this->assertSame($table, $modelClass::TABLE_NAME);
        $this->assertSame($fillable, $model->getFillable());
        $this->assertSame($hidden, $model->getHidden());

        $reflection = new ReflectionMethod($model, 'casts');
        $reflection->setAccessible(true);
        $this->assertSame($casts, $reflection->invoke($model));

        $constants = (new \ReflectionClass($modelClass))->getConstants();
        foreach ($fillable as $attribute) {
            $this->assertContains($attribute, $constants, "Fillable attribute {$attribute} should have a model constant");
        }
    }

    public function test_param_constants_are_unique_per_model(): void
    {
        foreach (array_column(self::modelProvider(), 0) as $modelClass) {
            $params = array_filter(
                (new \ReflectionClass($modelClass))->getConstants(),
                fn ($value, $key) => str_starts_with($key, 'PARAM_') && is_string($value),
                ARRAY_FILTER_USE_BOTH,
            );

            $this->assertSame(
                count($params),
                count(array_unique($params)),
                "Duplicate PARAM values in {$modelClass}",
            );
        }
    }
}
