<?php

namespace Tests\Feature\Database;

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AuthorsDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_table_exists_and_factory_creates_model(): void
    {
        $this->assertTrue(Schema::hasTable('authors'));

        $model = Author::factory()->create(['first_name' => '???', 'last_name' => '???????']);

        $this->assertModelExists($model);
        $this->assertDatabaseHas('authors', ['id' => $model->id]);
    }
}
