<?php

namespace Tests\Feature\Database;

use App\Models\SourceProduct;
use App\Models\SourceProductType;
use App\Models\SourceProductUserData;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Database-тесты: структура таблицы source_products на реальном pgsql —
 * NOT NULL внешние ключи и каскадное удаление.
 * Уникальность title проверяется только валидацией (Rule::unique).
 */
class SourceProductsDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_table_has_expected_columns(): void
    {
        $columns = Schema::getColumnListing(SourceProduct::TABLE_NAME);

        $this->assertContains(SourceProduct::PARAM_ID, $columns);
        $this->assertContains(SourceProduct::PARAM_TITLE, $columns);
        $this->assertContains(SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID, $columns);
        $this->assertContains(SourceProduct::PARAM_USER_CREATED_ID, $columns);
        $this->assertContains('created_at', $columns);
        $this->assertContains('updated_at', $columns);
    }

    public function test_title_column_is_string(): void
    {
        // pgsql возвращает native doctrine-типы: varchar, а не string
        $this->assertSame(
            'varchar',
            Schema::getColumnType(SourceProduct::TABLE_NAME, SourceProduct::PARAM_TITLE),
        );
    }

    public function test_database_does_not_enforce_unique_title(): void
    {
        $first = SourceProduct::factory()->create([SourceProduct::PARAM_TITLE => 'Кроссовки лето']);
        $second = SourceProduct::factory()->create([SourceProduct::PARAM_TITLE => 'Кроссовки лето']);

        $this->assertNotSame($first->id, $second->id);
        $this->assertSame(2, SourceProduct::query()->count());
    }

    public function test_source_product_type_id_is_not_nullable(): void
    {
        $this->expectException(QueryException::class);

        SourceProduct::query()->create([
            SourceProduct::PARAM_TITLE => 'Без типа',
            SourceProduct::PARAM_USER_CREATED_ID => User::factory()->create()->id,
        ]);
    }

    public function test_user_created_id_is_not_nullable(): void
    {
        $this->expectException(QueryException::class);

        SourceProduct::query()->create([
            SourceProduct::PARAM_TITLE => 'Без пользователя',
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID => SourceProductType::factory()->create()->id,
        ]);
    }

    /**
     * FK cascade: удаление типа каскадно удаляет source_products.
     */
    public function test_deleting_type_cascades_to_source_products(): void
    {
        $type = SourceProductType::factory()->create();
        $sourceProduct = SourceProduct::factory()->create([
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID => $type->id,
        ]);

        $type->delete();

        $this->assertModelMissing($sourceProduct);
        $this->assertSame(0, SourceProduct::query()->count());
    }

    /**
     * FK cascade: удаление source_product каскадно удаляет user_data.
     */
    public function test_deleting_source_product_cascades_to_user_data(): void
    {
        $sourceProduct = SourceProduct::factory()->create();
        SourceProductUserData::factory()->create([
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $sourceProduct->id,
        ]);

        $sourceProduct->delete();

        $this->assertSame(
            0,
            SourceProductUserData::query()->count(),
        );
    }
}
