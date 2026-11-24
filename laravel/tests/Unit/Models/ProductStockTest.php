<?php

namespace Tests\Unit\Models;

use App\Models\ProductStock;
use PHPUnit\Framework\TestCase;

class ProductStockTest extends TestCase
{
    public function test_table_name(): void
    {
        $model = new ProductStock;
        $this->assertSame('product_stocks', ProductStock::TABLE_NAME);
        $this->assertSame('product_stocks', $model->getTable());
    }
}
