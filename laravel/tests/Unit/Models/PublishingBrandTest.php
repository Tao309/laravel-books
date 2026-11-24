<?php

namespace Tests\Unit\Models;

use App\Models\PublishingBrand;
use PHPUnit\Framework\TestCase;

class PublishingBrandTest extends TestCase
{
    public function test_table_and_basic_contract(): void
    {
        $model = new PublishingBrand;
        $this->assertSame('publishing_brands', PublishingBrand::TABLE_NAME);
        $this->assertSame('publishing_brands', $model->getTable());
    }
}
