<?php

namespace Tests\Feature\Database;

use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ShopsDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_table_exists_and_factory_creates_model(): void
    {
        $this->assertTrue(Schema::hasTable('shops'));

        $model = Shop::factory()->create(['type' => 'shop-test', 'domain' => 'example.com', 'url' => 'https://example.com']);

        $this->assertModelExists($model);
        $this->assertDatabaseHas('shops', ['id' => $model->id]);
    }
}
