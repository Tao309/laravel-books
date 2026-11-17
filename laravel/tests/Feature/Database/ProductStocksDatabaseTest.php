<?php

namespace Tests\Feature\Database;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Database-тесты: таблица product_stocks на реальном pgsql.
 * PK/unique — три колонки (product_id, user_id, date);
 * qty имеет дефолт 1 (integer cast от default(true)); log — json.
 */
class ProductStocksDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_table_has_expected_columns(): void
    {
        $columns = Schema::getColumnListing(ProductStock::TABLE_NAME);

        $this->assertContains(ProductStock::PARAM_PRODUCT_ID, $columns);
        $this->assertContains(ProductStock::PARAM_USER_ID, $columns);
        $this->assertContains(ProductStock::PARAM_QTY, $columns);
        $this->assertContains(ProductStock::PARAM_DATE, $columns);
        $this->assertContains(ProductStock::PARAM_LOG, $columns);
    }

    public function test_database_enforces_unique_product_user_date(): void
    {
        $stock = ProductStock::factory()->create();

        $this->expectException(UniqueConstraintViolationException::class);

        ProductStock::factory()->create([
            ProductStock::PARAM_PRODUCT_ID => $stock->product_id,
            ProductStock::PARAM_USER_ID => $stock->user_id,
            ProductStock::PARAM_DATE => $stock->date,
        ]);
    }

    public function test_same_product_user_with_different_dates_is_allowed(): void
    {
        $stock = ProductStock::factory()->create();

        ProductStock::factory()->create([
            ProductStock::PARAM_PRODUCT_ID => $stock->product_id,
            ProductStock::PARAM_USER_ID => $stock->user_id,
            ProductStock::PARAM_DATE => now()->addDay()->startOfDay(),
        ]);

        $this->assertSame(2, ProductStock::query()->count());
    }

    /**
     * Документация дефекта миграции: $table->integer('qty')->default(true)
     * превращается в дефолт 1 — строка без qty получает количество 1.
     */
    public function test_qty_defaults_to_one(): void
    {
        $stock = ProductStock::query()->create([
            ProductStock::PARAM_PRODUCT_ID => Product::factory()->create()->id,
            ProductStock::PARAM_USER_ID => User::factory()->create()->id,
            ProductStock::PARAM_DATE => '2026-01-01 00:00:00',
        ]);

        // create() не возвращает DB-дефолты — перечитываем запросом
        // (refresh() на составном primaryKey не работает)
        $this->assertSame(
            1,
            ProductStock::query()
                ->where(ProductStock::PARAM_PRODUCT_ID, $stock->product_id)
                ->firstOrFail()
                ->qty,
        );
    }

    public function test_log_is_stored_as_json_and_cast_to_array(): void
    {
        $log = ['diffQty' => -5, 'newStatus' => 1];

        $stock = ProductStock::factory()->create([
            ProductStock::PARAM_LOG => $log,
        ]);

        $this->assertSame($log, $stock->log);
    }

    public function test_log_is_nullable(): void
    {
        $stock = ProductStock::factory()->create([
            ProductStock::PARAM_LOG => null,
        ]);

        $this->assertNull($stock->log);
    }

    public function test_deleting_product_cascades_to_stocks(): void
    {
        $stock = ProductStock::factory()->create();

        Product::query()->find($stock->product_id)?->delete();

        $this->assertDatabaseCount(ProductStock::TABLE_NAME, 0);
    }

    public function test_deleting_user_cascades_to_stocks(): void
    {
        $stock = ProductStock::factory()->create();

        User::query()->find($stock->user_id)?->delete();

        $this->assertDatabaseCount(ProductStock::TABLE_NAME, 0);
    }
}
