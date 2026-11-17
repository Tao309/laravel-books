<?php

namespace Tests\Feature\Database;

use App\Models\PublishingBrand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Database-тесты: структура таблицы и поведение фабрики на реальном pgsql.
 * Уникальность name проверяется только валидацией (Rule::unique) —
 * миграция не создаёт уникального индекса, что здесь и фиксируется.
 */
class PublishingBrandsDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_table_has_expected_columns(): void
    {
        $columns = Schema::getColumnListing(PublishingBrand::TABLE_NAME);

        $this->assertContains(PublishingBrand::PARAM_ID, $columns);
        $this->assertContains(PublishingBrand::PARAM_NAME, $columns);
        $this->assertContains('created_at', $columns);
        $this->assertContains('updated_at', $columns);
    }

    public function test_name_column_is_string(): void
    {
        // pgsql возвращает native doctrine-типы: varchar, а не string
        $this->assertSame(
            'varchar',
            Schema::getColumnType(PublishingBrand::TABLE_NAME, PublishingBrand::PARAM_NAME),
        );
    }

    public function test_database_does_not_enforce_unique_name(): void
    {
        $first = PublishingBrand::factory()->create([PublishingBrand::PARAM_NAME => 'Красная звезда']);
        $second = PublishingBrand::factory()->create([PublishingBrand::PARAM_NAME => 'Красная звезда']);

        $this->assertNotSame($first->id, $second->id);
        $this->assertSame(2, PublishingBrand::query()->count());
    }

    public function test_factory_names_fit_store_limits(): void
    {
        PublishingBrand::factory()->count(20)->create()->each(function (PublishingBrand $brand) {
            $length = mb_strlen($brand->name);

            $this->assertGreaterThanOrEqual(2, $length, "Name [{$brand->name}] is too short");
            $this->assertLessThanOrEqual(255, $length, "Name [{$brand->name}] is too long");
        });
    }
}
