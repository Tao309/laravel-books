<?php

namespace Tests\Unit\Models;

use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductStock;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

/**
 * Чистые unit-тесты модели Product: атрибуты, casts, computed-хелперы.
 * БД не используется — связи подставляются через setRelation().
 */
class ProductTest extends TestCase
{
    public function test_table_name(): void
    {
        $this->assertSame('products', (new Product)->getTable());
    }

    public function test_hides_foreign_key_columns(): void
    {
        $this->assertSame([
            Product::PARAM_SHOP_ID,
            Product::PARAM_CITY_ID,
            Product::PARAM_USER_CREATED_ID,
        ], (new Product)->getHidden());
    }

    public function test_fillable(): void
    {
        $this->assertSame([
            Product::PARAM_SHOP_PRODUCT_ID,
            Product::PARAM_SHOP_PRODUCT_CODE,
            Product::PARAM_TITLE,
            Product::PARAM_SHOP_ID,
            Product::PARAM_CITY_ID,
            Product::PARAM_USER_CREATED_ID,
            Product::PARAM_SOURCE_TYPE,
            Product::PARAM_SOURCE_ID,
        ], (new Product)->getFillable());
    }

    public function test_casts_computed_fields_to_integer(): void
    {
        $casts = (new Product)->getCasts();

        $this->assertSame('integer', $casts['minPrice']);
        $this->assertSame('integer', $casts['lastQty']);
    }

    public function test_min_price_returns_min_of_prices(): void
    {
        $product = new Product;
        $product->setRelation(Product::PARAM_PRICES, new Collection([
            (new ProductPrice)->forceFill([ProductPrice::PARAM_PRICE => 1500]),
            (new ProductPrice)->forceFill([ProductPrice::PARAM_PRICE => 1200]),
        ]));

        $this->assertSame(1200, $product->minPrice());
    }

    public function test_min_price_is_null_for_empty_prices(): void
    {
        $product = new Product;
        $product->setRelation(Product::PARAM_PRICES, new Collection);

        $this->assertNull($product->minPrice());
    }

    public function test_last_stock_returns_last_element(): void
    {
        $earlier = (new ProductStock)->forceFill([ProductStock::PARAM_QTY => 3]);
        $latest = (new ProductStock)->forceFill([ProductStock::PARAM_QTY => 7]);

        $product = new Product;
        // связи prices()/stocks() упорядочены по дате: последний элемент — самый свежий
        $product->setRelation(Product::PARAM_STOCKS, new Collection([$earlier, $latest]));

        $this->assertSame($latest, $product->lastStock());
        $this->assertSame(7, $product->lastQty());
    }

    public function test_last_qty_is_null_for_empty_stocks(): void
    {
        $product = new Product;
        $product->setRelation(Product::PARAM_STOCKS, new Collection);

        $this->assertNull($product->lastStock());
        $this->assertNull($product->lastQty());
    }

    public function test_with_is_loaded_automatically(): void
    {
        $property = new \ReflectionProperty(Product::class, 'with');

        $this->assertSame([
            Product::PARAM_SHOP,
            'userCreated',
        ], $property->getValue(new Product));
    }
}
