<?php

namespace Tests\Feature\Database;

use App\Models\City;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CitiesDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_table_exists_and_factory_creates_model(): void
    {
        $this->assertTrue(Schema::hasTable('cities'));

        $model = City::factory()->create(['code' => 'msk', 'title' => '??????']);

        $this->assertModelExists($model);
        $this->assertDatabaseHas('cities', ['id' => $model->id]);
    }
}
