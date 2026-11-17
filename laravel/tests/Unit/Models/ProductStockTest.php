<?php

namespace Tests\Unit\Models;

use App\Http\Resources\ProductStocks\ProductStockResource;
use App\Models\ProductStock;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Tests\TestCase;

/**
 * Чистые unit-тесты модели ProductStock.
 *
 * Расхождение с БД: $primaryKey = [product_id, user_id], фактический
 * PK таблицы — (product_id, user_id, date). log кастится в array.
 */
class ProductStockTest extends TestCase
{
    public function test_table_name_is_product_stocks(): void
    {
        $this->assertSame(ProductStock::TABLE_NAME, (new ProductStock)->getTable());
    }

    public function test_fillable_contains_foreign_keys_qty_date_and_log(): void
    {
        $this->assertSame(
            [
                ProductStock::PARAM_PRODUCT_ID,
                ProductStock::PARAM_USER_ID,
                ProductStock::PARAM_QTY,
                ProductStock::PARAM_DATE,
                ProductStock::PARAM_LOG,
            ],
            (new ProductStock)->getFillable(),
        );
    }

    public function test_hidden_hides_foreign_keys(): void
    {
        $this->assertSame(
            [ProductStock::PARAM_PRODUCT_ID, ProductStock::PARAM_USER_ID],
            (new ProductStock)->getHidden(),
        );
    }

    public function test_model_declares_two_column_primary_key(): void
    {
        $property = new \ReflectionProperty(ProductStock::class, 'primaryKey');

        // Документация дефекта: фактический PK в БД — (product_id, user_id, date)
        $this->assertSame(
            [ProductStock::PARAM_PRODUCT_ID, ProductStock::PARAM_USER_ID],
            $property->getValue(new ProductStock),
        );
    }

    public function test_incrementing_is_disabled(): void
    {
        $this->assertFalse((new ProductStock)->incrementing);
    }

    public function test_timestamps_are_disabled(): void
    {
        $this->assertFalse((new ProductStock)->timestamps);
    }

    public function test_date_and_log_casts(): void
    {
        $this->assertSame(
            [
                ProductStock::PARAM_DATE => 'datetime',
                ProductStock::PARAM_LOG => 'array',
            ],
            // casts() protected — публичный доступ только через getCasts()
            (new ProductStock)->getCasts(),
        );
    }

    public function test_use_resource_attribute_points_to_resource(): void
    {
        $attributes = (new \ReflectionClass(ProductStock::class))
            ->getAttributes(UseResource::class);

        $this->assertNotEmpty($attributes);
        $this->assertSame(ProductStockResource::class, $attributes[0]->newInstance()->class);
    }

    /**
     * Связь product() закомментирована — динамического вызова быть не должно.
     */
    public function test_product_relation_is_not_defined(): void
    {
        $this->assertFalse(
            (new \ReflectionClass(ProductStock::class))->hasMethod('product'),
        );
    }
}
