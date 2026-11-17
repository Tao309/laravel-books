<?php

namespace Tests\Feature\Database;

use App\Models\PaperType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Database-тесты: структура таблицы paper_types на реальном pgsql.
 * Уникальность label проверяется только валидацией (Rule::unique) —
 * миграция не создаёт уникального индекса, что здесь и фиксируется.
 */
class PaperTypesDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_table_has_expected_columns(): void
    {
        $columns = Schema::getColumnListing(PaperType::TABLE_NAME);

        $this->assertContains(PaperType::PARAM_ID, $columns);
        $this->assertContains(PaperType::PARAM_LABEL, $columns);
        $this->assertContains(PaperType::PARAM_DESCRIPTION, $columns);
        $this->assertContains('created_at', $columns);
        $this->assertContains('updated_at', $columns);
    }

    public function test_label_and_description_columns_are_strings(): void
    {
        // pgsql возвращает native doctrine-типы: varchar, а не string
        $this->assertSame(
            'varchar',
            Schema::getColumnType(PaperType::TABLE_NAME, PaperType::PARAM_LABEL),
        );
        $this->assertSame(
            'varchar',
            Schema::getColumnType(PaperType::TABLE_NAME, PaperType::PARAM_DESCRIPTION),
        );
    }

    public function test_database_does_not_enforce_unique_label(): void
    {
        $first = PaperType::factory()->create([PaperType::PARAM_LABEL => 'Мелованная']);
        $second = PaperType::factory()->create([PaperType::PARAM_LABEL => 'Мелованная']);

        $this->assertNotSame($first->id, $second->id);
        $this->assertSame(2, PaperType::query()->count());
    }

    public function test_description_is_nullable_in_database(): void
    {
        $paperType = PaperType::factory()->create([
            PaperType::PARAM_LABEL => 'Газетная',
            PaperType::PARAM_DESCRIPTION => null,
        ]);

        $this->assertNull($paperType->description);
        $this->assertModelExists($paperType);
    }

    /**
     * Валидация description — max:50; фабрика генерирует 8 слов,
     * уложиться в 50 символов может не всегда — проверяем только label
     * (min:2/max:20 для слов-названий: fake()->word() — одно слово).
     */
    public function test_factory_labels_are_single_words(): void
    {
        PaperType::factory()->count(20)->create()->each(function (PaperType $paperType) {
            $this->assertStringNotContainsString(
                ' ',
                $paperType->label,
                "Label [{$paperType->label}] is expected to be a single word",
            );
        });
    }
}
