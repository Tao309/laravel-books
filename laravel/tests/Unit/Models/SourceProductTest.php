<?php

namespace Tests\Unit\Models;

use App\Models\SourceProduct;
use PHPUnit\Framework\TestCase;

class SourceProductTest extends TestCase
{
    public function test_table_and_relations(): void
    {
        $sp = new SourceProduct;
        $this->assertSame('source_products', SourceProduct::TABLE_NAME);
        foreach (['sourceProductType', 'userCreated', 'products', 'sourceProductUserData'] as $relation) {
            $this->assertTrue(method_exists($sp, $relation), "Missing relation {$relation}");
        }
    }
}
