<?php

namespace Tests\Unit\Models;

use App\Models\PublishingHouse;
use PHPUnit\Framework\TestCase;

class PublishingHouseTest extends TestCase
{
    public function test_table_and_basic_contract(): void
    {
        $model = new PublishingHouse;
        $this->assertSame('publishing_houses', PublishingHouse::TABLE_NAME);
        $this->assertSame('publishing_houses', $model->getTable());
    }
}
