<?php

namespace Tests\Feature\Database;

use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Database-тесты: таблица product_prices на реальном pgsql.
 * PK/unique — три колонки (product_id, user_id, date);
 * price имеет дефолт 1 (integer cast от default(true)).
 */
class ProductPricesDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_table_has_expected_columns(): void
    {
        $columns = Schema::getColumnListing(ProductPrice::TABLE_NAME);

        $this->assertContains(ProductPrice::PARAM_PRODUCT_ID, $columns);
        $this->assertContains(ProductPrice::PARAM_USER_ID, $columns);
        $this->assertContains(ProductPrice::PARAM_PRICE, $columns);
        $this->assertContains(ProductPrice::PARAM_DATE, $columns);
    }

    public function test_database_enforces_unique_product_user_date(): void
    {
        $price = ProductPrice::factory()->create();

        $this->expectException(UniqueConstraintViolationException::class);

        ProductPrice::factory()->create([
            ProductPrice::PARAM_PRODUCT_ID => $price->product_id,
            ProductPrice::PARAM_USER_ID => $price->user_id,
            ProductPrice::PARAM_DATE => $price->date,
        ]);
    }

    public function test_same_product_user_with_different_dates_is_allowed(): void
    {
        $price = ProductPrice::factory()->create();

        ProductPrice::factory()->create([
            ProductPrice::PARAM_PRODUCT_ID => $price->product_id,
            ProductPrice::PARAM_USER_ID => $price->user_id,
            ProductPrice::PARAM_DATE => now()->addDay()->startOfDay(),
        ]);

        $this->assertSame(2, ProductPrice::query()->count());
    }

    /**
     * Документация дефекта миграции: $table->integer('price')->default(true)
     * превращается в дефолт 1 — строка без цены получает цену 1.
     */
    public function test_price_defaults_to_one(): void
    {
        $price = ProductPrice::query()->create([
            ProductPrice::PARAM_PRODUCT_ID => Product::factory()->create()->id,
            ProductPrice::PARAM_USER_ID => User::factory()->create()->id,
            ProductPrice::PARAM_DATE => '2026-01-01 00:00:00',
        ]);

        // create() не возвращает DB-дефолты — перечитываем запросом
        // (refresh() на составном primaryKey не работает)
        $this->assertSame(
            1,
            ProductPrice::query()
                ->where(ProductPrice::PARAM_PRODUCT_ID, $price->product_id)
                ->firstOrFail()
                ->price,
        );
    }

    public function test_deleting_product_cascades_to_prices(): void
    {
        $price = ProductPrice::factory()->create();

        Product::query()->find($price->product_id)?->delete();

        $this->assertDatabaseCount(ProductPrice::TABLE_NAME, 0);
    }

    public function test_deleting_user_cascades_to_prices(): void
    {
        $price = ProductPrice::factory()->create();

        User::query()->find($price->user_id)?->delete();

        $this->assertDatabaseCount(ProductPrice::TABLE_NAME, 0);
    }
}
