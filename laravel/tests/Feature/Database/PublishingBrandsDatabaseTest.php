<?php

namespace Tests\Feature\Database;

use App\Models\PublishingBrand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PublishingBrandsDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_table_exists_and_factory_creates_model(): void
    {
        $this->assertTrue(Schema::hasTable('publishing_brands'));

        $model = PublishingBrand::factory()->create(['name' => '?????']);

        $this->assertModelExists($model);
        $this->assertDatabaseHas('publishing_brands', ['id' => $model->id]);
    }
}
