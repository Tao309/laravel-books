<?php

namespace Tests\Feature\Database;

use App\Models\PaperType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PaperTypesDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_table_exists_and_factory_creates_model(): void
    {
        $this->assertTrue(Schema::hasTable('paper_types'));

        $model = PaperType::factory()->create(['label' => '?????']);

        $this->assertModelExists($model);
        $this->assertDatabaseHas('paper_types', ['id' => $model->id]);
    }
}
