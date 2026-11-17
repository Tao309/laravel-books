<?php

namespace Tests\Feature\Database;

use App\Models\BindingType;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Database-тесты: структура таблицы binding_types и реальное поведение
 * unique-индекса label на pgsql (валидация Rule::unique его дублирует).
 */
class BindingTypesDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_table_has_expected_columns(): void
    {
        $columns = Schema::getColumnListing(BindingType::TABLE_NAME);

        $this->assertContains(BindingType::PARAM_ID, $columns);
        $this->assertContains(BindingType::PARAM_LABEL, $columns);
        $this->assertContains('created_at', $columns);
        $this->assertContains('updated_at', $columns);
    }

    public function test_label_column_is_string(): void
    {
        // pgsql возвращает native doctrine-типы: varchar, а не string
        $this->assertSame(
            'varchar',
            Schema::getColumnType(BindingType::TABLE_NAME, BindingType::PARAM_LABEL),
        );
    }

    /**
     * В отличие от справочников brands/houses у binding_types в миграции
     * есть unique-индекс — дубликат label запрещён на уровне БД.
     */
    public function test_database_enforces_unique_label(): void
    {
        BindingType::factory()->create([BindingType::PARAM_LABEL => 'Твёрдый переплёт']);

        $this->expectException(UniqueConstraintViolationException::class);

        BindingType::factory()->create([BindingType::PARAM_LABEL => 'Твёрдый переплёт']);
    }
}
