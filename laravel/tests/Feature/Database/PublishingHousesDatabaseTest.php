<?php

namespace Tests\Feature\Database;

use App\Models\PublishingHouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Database-тесты: структура таблицы и поведение фабрики на реальном pgsql.
 * Уникальность name проверяется только валидацией (Rule::unique) —
 * миграция не создаёт уникального индекса, что здесь и фиксируется.
 */
class PublishingHousesDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_table_has_expected_columns(): void
    {
        $columns = Schema::getColumnListing(PublishingHouse::TABLE_NAME);

        $this->assertContains(PublishingHouse::PARAM_ID, $columns);
        $this->assertContains(PublishingHouse::PARAM_NAME, $columns);
        $this->assertContains('created_at', $columns);
        $this->assertContains('updated_at', $columns);
    }

    public function test_name_column_is_string(): void
    {
        // pgsql возвращает native doctrine-типы: varchar, а не string
        $this->assertSame(
            'varchar',
            Schema::getColumnType(PublishingHouse::TABLE_NAME, PublishingHouse::PARAM_NAME),
        );
    }

    public function test_database_does_not_enforce_unique_name(): void
    {
        $first = PublishingHouse::factory()->create([PublishingHouse::PARAM_NAME => 'Полярная звезда']);
        $second = PublishingHouse::factory()->create([PublishingHouse::PARAM_NAME => 'Полярная звезда']);

        $this->assertNotSame($first->id, $second->id);
        $this->assertSame(2, PublishingHouse::query()->count());
    }

    public function test_factory_names_fit_limits(): void
    {
        PublishingHouse::factory()->count(20)->create()->each(function (PublishingHouse $house) {
            $length = mb_strlen($house->name);

            $this->assertGreaterThanOrEqual(2, $length, "Name [{$house->name}] is too short");
            $this->assertLessThanOrEqual(255, $length, "Name [{$house->name}] is too long");
        });
    }
}
