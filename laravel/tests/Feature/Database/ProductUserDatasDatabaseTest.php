<?php

namespace Tests\Feature\Database;

use App\Models\Product;
use App\Models\ProductUserData;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Database-тесты: таблица product_user_data на реальном pgsql —
 * составной PK, дефолты флагов и каскадные FK.
 */
class ProductUserDatasDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_table_has_expected_columns(): void
    {
        $columns = Schema::getColumnListing(ProductUserData::TABLE_NAME);

        $this->assertContains(ProductUserData::PARAM_PRODUCT_ID, $columns);
        $this->assertContains(ProductUserData::PARAM_USER_ID, $columns);
        $this->assertContains(ProductUserData::PARAM_AVAILABLE, $columns);
        $this->assertContains(ProductUserData::PARAM_NOT_AVAILABLE_DATE_FROM, $columns);
        $this->assertContains(ProductUserData::PARAM_AVAILABLE_DATE_FROM, $columns);
        $this->assertContains(ProductUserData::PARAM_LISTEN_PRICE_VALUE, $columns);
        $this->assertContains(ProductUserData::PARAM_LISTEN_QTY_VALUE, $columns);
        $this->assertContains(ProductUserData::PARAM_RELEASE_DATE, $columns);
        $this->assertContains(ProductUserData::PARAM_IS_ARCHIVE, $columns);
        $this->assertContains(ProductUserData::PARAM_COMMENT, $columns);
    }

    public function test_database_enforces_unique_product_user_pair(): void
    {
        $userData = ProductUserData::factory()->create();

        $this->expectException(UniqueConstraintViolationException::class);

        ProductUserData::factory()->create([
            ProductUserData::PARAM_PRODUCT_ID => $userData->product_id,
            ProductUserData::PARAM_USER_ID => $userData->user_id,
        ]);
    }

    /**
     * Дефолты на уровне БД: available=true, is_archive=false.
     */
    public function test_flags_have_database_defaults(): void
    {
        $product = Product::factory()->create();
        $user = User::factory()->create();

        ProductUserData::query()->create([
            ProductUserData::PARAM_PRODUCT_ID => $product->id,
            ProductUserData::PARAM_USER_ID => $user->id,
        ]);

        $row = ProductUserData::query()
            ->where(ProductUserData::PARAM_PRODUCT_ID, $product->id)
            ->first();

        $this->assertTrue($row->available);
        $this->assertFalse($row->is_archive);
    }

    public function test_deleting_product_cascades_to_user_data(): void
    {
        $userData = ProductUserData::factory()->create();

        $userData->product->delete();

        // assertModelMissing неприменим: составной PK (product_id, user_id)
        // роняет getKey() TypeError'ом; счётчик строк фиксирует каскад.
        $this->assertDatabaseCount(ProductUserData::TABLE_NAME, 0);
    }

    public function test_deleting_user_cascades_to_user_data(): void
    {
        $userData = ProductUserData::factory()->create();

        // связи user() у модели нет — удаляем по id
        User::query()->find($userData->user_id)?->delete();

        // assertModelMissing неприменим: составной PK роняет getKey().
        $this->assertDatabaseCount(ProductUserData::TABLE_NAME, 0);
    }
}
