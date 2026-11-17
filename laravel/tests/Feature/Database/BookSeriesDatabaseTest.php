<?php

namespace Tests\Feature\Database;

use App\Models\BookSeries;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Database-тесты: структура таблицы books_series на реальном pgsql.
 * Уникальность name проверяется только валидацией (Rule::unique) —
 * миграция не создаёт уникального индекса, что здесь и фиксируется.
 */
class BookSeriesDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_table_has_expected_columns(): void
    {
        $columns = Schema::getColumnListing(BookSeries::TABLE_NAME);

        $this->assertContains(BookSeries::PARAM_ID, $columns);
        $this->assertContains(BookSeries::PARAM_NAME, $columns);
        $this->assertContains('created_at', $columns);
        $this->assertContains('updated_at', $columns);
    }

    public function test_name_column_is_string(): void
    {
        // pgsql возвращает native doctrine-типы: varchar, а не string
        $this->assertSame(
            'varchar',
            Schema::getColumnType(BookSeries::TABLE_NAME, BookSeries::PARAM_NAME),
        );
    }

    public function test_database_does_not_enforce_unique_name(): void
    {
        $first = BookSeries::factory()->create([BookSeries::PARAM_NAME => 'Мировая классика']);
        $second = BookSeries::factory()->create([BookSeries::PARAM_NAME => 'Мировая классика']);

        $this->assertNotSame($first->id, $second->id);
        $this->assertSame(2, BookSeries::query()->count());
    }

    public function test_factory_names_fit_validation_limits(): void
    {
        BookSeries::factory()->count(20)->create()->each(function (BookSeries $series) {
            $length = mb_strlen($series->name);

            $this->assertGreaterThanOrEqual(2, $length, "Name [{$series->name}] is too short");
            $this->assertLessThanOrEqual(255, $length, "Name [{$series->name}] is too long");
        });
    }
}
