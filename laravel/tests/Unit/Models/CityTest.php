<?php

namespace Tests\Unit\Models;

use App\Models\City;
use PHPUnit\Framework\TestCase;

class CityTest extends TestCase
{
    public function test_table_and_basic_contract(): void
    {
        $model = new City;
        $this->assertSame('cities', City::TABLE_NAME);
        $this->assertSame('cities', $model->getTable());
    }
}
