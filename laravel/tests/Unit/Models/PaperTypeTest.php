<?php

namespace Tests\Unit\Models;

use App\Models\PaperType;
use PHPUnit\Framework\TestCase;

class PaperTypeTest extends TestCase
{
    public function test_table_and_basic_contract(): void
    {
        $model = new PaperType;
        $this->assertSame('paper_types', PaperType::TABLE_NAME);
        $this->assertSame('paper_types', $model->getTable());
    }
}
