<?php

namespace Tests\Feature\Database;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ProductsDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_table_exists_and_factory_creates_product(): void
    {
        $this->assertTrue(Schema::hasTable(Product::TABLE_NAME));
        $product = Product::factory()->create();
        $this->assertModelExists($product);
    }
}
