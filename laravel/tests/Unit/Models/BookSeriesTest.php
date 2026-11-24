<?php

namespace Tests\Unit\Models;

use App\Models\BookSeries;
use PHPUnit\Framework\TestCase;

class BookSeriesTest extends TestCase
{
    public function test_table_and_basic_contract(): void
    {
        $model = new BookSeries;
        $this->assertSame('book_series', BookSeries::TABLE_NAME);
        $this->assertSame('book_series', $model->getTable());
    }
}
