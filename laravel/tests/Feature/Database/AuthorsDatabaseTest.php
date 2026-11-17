<?php

namespace Tests\Feature\Database;

use App\Models\Author;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Database-тесты: структура таблицы authors и реальное поведение
 * составного unique-индекса (first_name, last_name) на pgsql.
 */
class AuthorsDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_table_has_expected_columns(): void
    {
        $columns = Schema::getColumnListing(Author::TABLE_NAME);

        $this->assertContains(Author::PARAM_ID, $columns);
        $this->assertContains(Author::PARAM_FIRST_NAME, $columns);
        $this->assertContains(Author::PARAM_LAST_NAME, $columns);
        $this->assertContains('created_at', $columns);
        $this->assertContains('updated_at', $columns);
    }

    public function test_name_columns_are_strings(): void
    {
        // pgsql возвращает native doctrine-типы: varchar, а не string
        $this->assertSame(
            'varchar',
            Schema::getColumnType(Author::TABLE_NAME, Author::PARAM_FIRST_NAME),
        );
        $this->assertSame(
            'varchar',
            Schema::getColumnType(Author::TABLE_NAME, Author::PARAM_LAST_NAME),
        );
    }

    /**
     * В отличие от справочников brands/houses у authors в миграции
     * есть составной unique-индекс — дубликат пары запрещён на уровне БД.
     */
    public function test_database_enforces_unique_first_plus_last_name(): void
    {
        Author::factory()->create([
            Author::PARAM_FIRST_NAME => 'Лев',
            Author::PARAM_LAST_NAME => 'Толстой',
        ]);

        $this->expectException(UniqueConstraintViolationException::class);

        Author::factory()->create([
            Author::PARAM_FIRST_NAME => 'Лев',
            Author::PARAM_LAST_NAME => 'Толстой',
        ]);
    }

    public function test_same_first_name_with_different_last_name_is_allowed(): void
    {
        Author::factory()->create([
            Author::PARAM_FIRST_NAME => 'Лев',
            Author::PARAM_LAST_NAME => 'Толстой',
        ]);
        $second = Author::factory()->create([
            Author::PARAM_FIRST_NAME => 'Лев',
            Author::PARAM_LAST_NAME => 'Ландау',
        ]);

        $this->assertModelExists($second);
        $this->assertSame(2, Author::query()->count());
    }

    /**
     * Postgres по умолчанию считает NULL различными: пары
     * (first_name, NULL) не конфликтуют, хотя валидация такая же.
     */
    public function test_duplicate_first_name_with_null_last_name_is_allowed(): void
    {
        Author::factory()->create([
            Author::PARAM_FIRST_NAME => 'Безымянный',
            Author::PARAM_LAST_NAME => null,
        ]);
        $second = Author::factory()->create([
            Author::PARAM_FIRST_NAME => 'Безымянный',
            Author::PARAM_LAST_NAME => null,
        ]);

        $this->assertModelExists($second);
        $this->assertSame(2, Author::query()->count());
    }
}
