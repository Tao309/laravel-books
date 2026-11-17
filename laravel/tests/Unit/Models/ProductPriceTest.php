<?php

namespace Tests\Unit\Models;

use App\Http\Resources\ProductPrices\ProductPriceResource;
use App\Models\ProductPrice;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Tests\TestCase;

/**
 * Чистые unit-тесты модели ProductPrice.
 *
 * Расхождение с БД: в модели $primaryKey = [product_id, user_id],
 * а фактический PK таблицы — три колонки (включая date).
 */
class ProductPriceTest extends TestCase
{
    public function test_table_name_is_product_prices(): void
    {
        $this->assertSame(ProductPrice::TABLE_NAME, (new ProductPrice)->getTable());
    }

    public function test_fillable_contains_foreign_keys_price_and_date(): void
    {
        $this->assertSame(
            [
                ProductPrice::PARAM_PRODUCT_ID,
                ProductPrice::PARAM_USER_ID,
                ProductPrice::PARAM_PRICE,
                ProductPrice::PARAM_DATE,
            ],
            (new ProductPrice)->getFillable(),
        );
    }

    public function test_hidden_hides_foreign_keys(): void
    {
        $this->assertSame(
            [ProductPrice::PARAM_PRODUCT_ID, ProductPrice::PARAM_USER_ID],
            (new ProductPrice)->getHidden(),
        );
    }

    public function test_model_declares_two_column_primary_key(): void
    {
        $property = new \ReflectionProperty(ProductPrice::class, 'primaryKey');

        // Документация дефекта: фактический PK в БД — (product_id, user_id, date)
        $this->assertSame(
            [ProductPrice::PARAM_PRODUCT_ID, ProductPrice::PARAM_USER_ID],
            $property->getValue(new ProductPrice),
        );
    }

    public function test_incrementing_is_disabled(): void
    {
        $this->assertFalse((new ProductPrice)->incrementing);
    }

    public function test_timestamps_are_disabled(): void
    {
        $this->assertFalse((new ProductPrice)->timestamps);
    }

    public function test_date_is_datetime_cast(): void
    {
        $this->assertSame(
            [ProductPrice::PARAM_DATE => 'datetime'],
            // casts() protected — публичный доступ только через getCasts()
            (new ProductPrice)->getCasts(),
        );
    }

    public function test_use_resource_attribute_points_to_resource(): void
    {
        $attributes = (new \ReflectionClass(ProductPrice::class))
            ->getAttributes(UseResource::class);

        $this->assertNotEmpty($attributes);
        $this->assertSame(ProductPriceResource::class, $attributes[0]->newInstance()->class);
    }

    /**
     * Связь product() закомментирована — динамического вызова быть не должно.
     */
    public function test_product_relation_is_not_defined(): void
    {
        $this->assertFalse(
            (new \ReflectionClass(ProductPrice::class))->hasMethod('product'),
        );
    }
}
