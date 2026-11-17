<?php

namespace Tests\Unit\Models;

use App\Http\Resources\ProductUserDatas\ProductUserDataResource;
use App\Models\Product;
use App\Models\ProductUserData;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

/**
 * Чистые unit-тесты модели ProductUserData: конфигурация без обращения к БД.
 */
class ProductUserDataTest extends TestCase
{
    public function test_table_name_is_product_user_data(): void
    {
        $this->assertSame(ProductUserData::TABLE_NAME, (new ProductUserData)->getTable());
    }

    public function test_fillable_contains_all_ten_columns(): void
    {
        $this->assertSame(
            [
                ProductUserData::PARAM_PRODUCT_ID,
                ProductUserData::PARAM_USER_ID,
                ProductUserData::PARAM_AVAILABLE,
                ProductUserData::PARAM_NOT_AVAILABLE_DATE_FROM,
                ProductUserData::PARAM_AVAILABLE_DATE_FROM,
                ProductUserData::PARAM_LISTEN_PRICE_VALUE,
                ProductUserData::PARAM_LISTEN_QTY_VALUE,
                ProductUserData::PARAM_RELEASE_DATE,
                ProductUserData::PARAM_IS_ARCHIVE,
                ProductUserData::PARAM_COMMENT,
            ],
            (new ProductUserData)->getFillable(),
        );
    }

    public function test_hidden_hides_foreign_keys(): void
    {
        $this->assertSame(
            [ProductUserData::PARAM_PRODUCT_ID, ProductUserData::PARAM_USER_ID],
            (new ProductUserData)->getHidden(),
        );
    }

    public function test_primary_key_is_composite(): void
    {
        $property = new \ReflectionProperty(ProductUserData::class, 'primaryKey');

        $this->assertSame(
            [ProductUserData::PARAM_PRODUCT_ID, ProductUserData::PARAM_USER_ID],
            $property->getValue(new ProductUserData),
        );
    }

    public function test_incrementing_is_disabled(): void
    {
        $this->assertFalse((new ProductUserData)->incrementing);
    }

    public function test_three_datetime_casts(): void
    {
        $this->assertSame(
            [
                ProductUserData::PARAM_NOT_AVAILABLE_DATE_FROM => 'datetime',
                ProductUserData::PARAM_AVAILABLE_DATE_FROM => 'datetime',
                ProductUserData::PARAM_RELEASE_DATE => 'datetime',
            ],
            // casts() protected — публичный доступ только через getCasts()
            (new ProductUserData)->getCasts(),
        );
    }

    public function test_use_resource_attribute_points_to_resource(): void
    {
        $attributes = (new \ReflectionClass(ProductUserData::class))
            ->getAttributes(UseResource::class);

        $this->assertNotEmpty($attributes);
        $this->assertSame(ProductUserDataResource::class, $attributes[0]->newInstance()->class);
    }

    public function test_product_relation_is_belongs_to(): void
    {
        $relation = (new ProductUserData)->product();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertSame(ProductUserData::PARAM_PRODUCT_ID, $relation->getForeignKeyName());
        $this->assertSame(Product::class, $relation->getRelated()::class);
    }
}
