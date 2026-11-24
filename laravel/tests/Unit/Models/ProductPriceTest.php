<?php

namespace Tests\Unit\Models;

use App\Models\ProductPrice;
use PHPUnit\Framework\TestCase;

class ProductPriceTest extends TestCase
{
    public function test_table_name(): void
    {
        $model = new ProductPrice;
        $this->assertSame('product_prices', ProductPrice::TABLE_NAME);
        $this->assertSame('product_prices', $model->getTable());
    }
}
