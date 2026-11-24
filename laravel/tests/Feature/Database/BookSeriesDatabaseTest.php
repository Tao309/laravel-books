<?php

namespace Tests\Feature\Database;

use App\Models\BookSeries;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class BookSeriesDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_table_exists_and_factory_creates_model(): void
    {
        $this->assertTrue(Schema::hasTable('book_series'));

        $model = BookSeries::factory()->create(['name' => '?????']);

        $this->assertModelExists($model);
        $this->assertDatabaseHas('book_series', ['id' => $model->id]);
    }
}
