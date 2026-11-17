<?php

namespace Tests\Feature\Database;

use App\Models\SourceProductType;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Database-тесты: структура таблицы source_product_types на реальном pgsql.
 * В миграции два unique-индекса (code, name) — дубликаты запрещены на уровне БД.
 */
class SourceProductTypesDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_table_has_expected_columns(): void
    {
        $columns = Schema::getColumnListing(SourceProductType::TABLE_NAME);

        $this->assertContains(SourceProductType::PARAM_ID, $columns);
        $this->assertContains(SourceProductType::PARAM_CODE, $columns);
        $this->assertContains(SourceProductType::PARAM_NAME, $columns);
        $this->assertContains('created_at', $columns);
        $this->assertContains('updated_at', $columns);
    }

    public function test_code_and_name_columns_are_strings(): void
    {
        // pgsql возвращает native doctrine-типы: varchar, а не string
        $this->assertSame(
            'varchar',
            Schema::getColumnType(SourceProductType::TABLE_NAME, SourceProductType::PARAM_CODE),
        );
        $this->assertSame(
            'varchar',
            Schema::getColumnType(SourceProductType::TABLE_NAME, SourceProductType::PARAM_NAME),
        );
    }

    public function test_database_enforces_unique_code(): void
    {
        SourceProductType::factory()->create([SourceProductType::PARAM_CODE => 'shoes']);

        $this->expectException(UniqueConstraintViolationException::class);

        SourceProductType::factory()->create([SourceProductType::PARAM_CODE => 'shoes']);
    }

    public function test_database_enforces_unique_name(): void
    {
        SourceProductType::factory()->create([
            SourceProductType::PARAM_CODE => 'unique-code-one',
            SourceProductType::PARAM_NAME => 'Обувь',
        ]);

        $this->expectException(UniqueConstraintViolationException::class);

        SourceProductType::factory()->create([
            SourceProductType::PARAM_CODE => 'unique-code-two',
            SourceProductType::PARAM_NAME => 'Обувь',
        ]);
    }

    public function test_named_states_create_deterministic_types(): void
    {
        $shoes = SourceProductType::factory()->shoes()->create();
        $shirts = SourceProductType::factory()->shirts()->create();
        $food = SourceProductType::factory()->food()->create();

        $this->assertSame('shoes', $shoes->code);
        $this->assertSame('shirts', $shirts->code);
        $this->assertSame('food', $food->code);
        $this->assertSame(3, SourceProductType::query()->count());
    }
}
