<?php

namespace Tests\Unit\Models;

use App\Models\ProductUserData;
use PHPUnit\Framework\TestCase;

class ProductUserDataTest extends TestCase
{
    public function test_table_name(): void
    {
        $model = new ProductUserData;
        $this->assertSame('product_user_data', ProductUserData::TABLE_NAME);
        $this->assertSame('product_user_data', $model->getTable());
    }
}
