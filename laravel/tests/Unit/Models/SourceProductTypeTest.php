<?php

namespace Tests\Unit\Models;

use App\Models\SourceProductType;
use PHPUnit\Framework\TestCase;

class SourceProductTypeTest extends TestCase
{
    public function test_table_and_basic_contract(): void
    {
        $model = new SourceProductType;
        $this->assertSame('source_product_types', SourceProductType::TABLE_NAME);
        $this->assertSame('source_product_types', $model->getTable());
    }
}
