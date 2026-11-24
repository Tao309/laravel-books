<?php

namespace Tests\Feature\Database;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class BooksDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_table_exists_and_factory_creates_book(): void
    {
        $this->assertTrue(Schema::hasTable(Book::TABLE_NAME));
        $book = Book::factory()->create();
        $this->assertModelExists($book);
    }
}
