<?php

namespace Tests\Feature\Database;

use App\Models\BindingType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class BindingTypesDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_table_exists_and_factory_creates_model(): void
    {
        $this->assertTrue(Schema::hasTable('binding_types'));

        $model = BindingType::factory()->create(['label' => '???????']);

        $this->assertModelExists($model);
        $this->assertDatabaseHas('binding_types', ['id' => $model->id]);
    }
}
