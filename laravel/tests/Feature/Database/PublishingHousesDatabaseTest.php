<?php

namespace Tests\Feature\Database;

use App\Models\PublishingHouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PublishingHousesDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_table_exists_and_factory_creates_model(): void
    {
        $this->assertTrue(Schema::hasTable('publishing_houses'));

        $model = PublishingHouse::factory()->create(['name' => '???']);

        $this->assertModelExists($model);
        $this->assertDatabaseHas('publishing_houses', ['id' => $model->id]);
    }
}
