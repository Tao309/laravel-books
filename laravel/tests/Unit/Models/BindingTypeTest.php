<?php

namespace Tests\Unit\Models;

use App\Models\BindingType;
use PHPUnit\Framework\TestCase;

class BindingTypeTest extends TestCase
{
    public function test_table_and_basic_contract(): void
    {
        $model = new BindingType;
        $this->assertSame('binding_types', BindingType::TABLE_NAME);
        $this->assertSame('binding_types', $model->getTable());
    }
}
