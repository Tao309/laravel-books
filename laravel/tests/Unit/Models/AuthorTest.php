<?php

namespace Tests\Unit\Models;

use App\Models\Author;
use PHPUnit\Framework\TestCase;

class AuthorTest extends TestCase
{
    public function test_table_and_basic_contract(): void
    {
        $model = new Author;
        $this->assertSame('authors', Author::TABLE_NAME);
        $this->assertSame('authors', $model->getTable());
    }
}
