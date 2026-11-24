<?php

namespace Tests\Unit\Models;

use App\Models\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function test_table_and_relations(): void
    {
        $p = new Product;
        $this->assertSame('products', Product::TABLE_NAME);
        foreach (['city', 'shop', 'userCreated', 'productUserData', 'prices', 'stocks', 'source'] as $relation) {
            $this->assertTrue(method_exists($p, $relation), "Missing relation {$relation}");
        }
    }
}
