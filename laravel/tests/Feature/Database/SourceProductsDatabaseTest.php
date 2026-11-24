<?php

namespace Tests\Feature\Database;

use App\Models\SourceProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SourceProductsDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_table_exists_and_factory_creates_source_product(): void
    {
        $this->assertTrue(Schema::hasTable(SourceProduct::TABLE_NAME));
        $sp = SourceProduct::factory()->create();
        $this->assertModelExists($sp);
    }
}
