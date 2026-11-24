<?php

namespace Tests\Unit\Models;

use App\Models\SourceProductUserData;
use PHPUnit\Framework\TestCase;

class SourceProductUserDataTest extends TestCase
{
    public function test_table_name(): void
    {
        $model = new SourceProductUserData;
        $this->assertSame('source_product_user_data', SourceProductUserData::TABLE_NAME);
        $this->assertSame('source_product_user_data', $model->getTable());
    }
}
