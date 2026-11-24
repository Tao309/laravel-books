<?php

namespace Tests\Unit\Models;

use App\Models\Shop;
use PHPUnit\Framework\TestCase;

class ShopTest extends TestCase
{
    public function test_table_and_basic_contract(): void
    {
        $model = new Shop;
        $this->assertSame('shops', Shop::TABLE_NAME);
        $this->assertSame('shops', $model->getTable());
    }
}
