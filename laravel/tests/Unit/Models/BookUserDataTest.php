<?php

namespace Tests\Unit\Models;

use App\Models\BookUserData;
use PHPUnit\Framework\TestCase;

class BookUserDataTest extends TestCase
{
    public function test_table_name(): void
    {
        $model = new BookUserData;
        $this->assertSame('book_user_data', BookUserData::TABLE_NAME);
        $this->assertSame('book_user_data', $model->getTable());
    }
}
