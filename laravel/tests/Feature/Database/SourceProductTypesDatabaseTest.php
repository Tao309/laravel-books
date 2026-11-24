<?php

namespace Tests\Feature\Database;

use App\Models\SourceProductType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SourceProductTypesDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_table_exists_and_factory_creates_model(): void
    {
        $this->assertTrue(Schema::hasTable('source_product_types'));

        $model = SourceProductType::factory()->create(['name' => '???', 'code' => 'type']);

        $this->assertModelExists($model);
        $this->assertDatabaseHas('source_product_types', ['id' => $model->id]);
    }
}
