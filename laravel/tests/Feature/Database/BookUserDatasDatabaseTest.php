<?php

namespace Tests\Feature\Database;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class BookUserDatasDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_table_exists(): void
    {
        $this->assertTrue(Schema::hasTable('book_user_data'));
    }
}
