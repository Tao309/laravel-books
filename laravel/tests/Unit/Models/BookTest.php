<?php

namespace Tests\Unit\Models;

use App\Models\Book;
use PHPUnit\Framework\TestCase;

class BookTest extends TestCase
{
    public function test_table_and_relations(): void
    {
        $book = new Book;

        $this->assertSame('books', Book::TABLE_NAME);
        foreach (['bindingType', 'paperType', 'publishingHouse', 'publishingBrand', 'bookSeries', 'userCreated', 'authors', 'products', 'bookUserData'] as $relation) {
            $this->assertTrue(method_exists($book, $relation), "Missing relation {$relation}");
        }
    }
}
