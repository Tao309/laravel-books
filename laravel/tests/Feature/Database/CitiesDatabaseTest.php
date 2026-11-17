<?php

namespace Tests\Feature\Database;

use App\Models\City;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Database-тесты: структура таблицы cities на реальном pgsql.
 * Уникальность code/title проверяется только валидацией (Rule::unique) —
 * миграция не создаёт уникальных индексов, что здесь и фиксируется.
 */
class CitiesDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_table_has_expected_columns(): void
    {
        $columns = Schema::getColumnListing(City::TABLE_NAME);

        $this->assertContains(City::PARAM_ID, $columns);
        $this->assertContains(City::PARAM_CODE, $columns);
        $this->assertContains(City::PARAM_TITLE, $columns);
        $this->assertContains('created_at', $columns);
        $this->assertContains('updated_at', $columns);
    }

    public function test_code_and_title_columns_are_strings(): void
    {
        // pgsql возвращает native doctrine-типы: varchar, а не string
        $this->assertSame(
            'varchar',
            Schema::getColumnType(City::TABLE_NAME, City::PARAM_CODE),
        );
        $this->assertSame(
            'varchar',
            Schema::getColumnType(City::TABLE_NAME, City::PARAM_TITLE),
        );
    }

    public function test_database_does_not_enforce_unique_code(): void
    {
        $first = City::factory()->create([City::PARAM_CODE => 'KZN']);
        $second = City::factory()->create([City::PARAM_CODE => 'KZN']);

        $this->assertNotSame($first->id, $second->id);
        $this->assertSame(2, City::query()->count());
    }

    public function test_database_does_not_enforce_unique_title(): void
    {
        $first = City::factory()->create([City::PARAM_TITLE => 'Казань']);
        $second = City::factory()->create([City::PARAM_TITLE => 'Казань']);

        $this->assertNotSame($first->id, $second->id);
        $this->assertSame(2, City::query()->count());
    }

    /**
     * Код города в валидации ограничен max:4, колонка — varchar(10);
     * фабрика генерирует коды из первых трёх букв названия (<= 4).
     */
    public function test_factory_codes_fit_validation_limit(): void
    {
        City::factory()->count(20)->create()->each(function (City $city) {
            $this->assertLessThanOrEqual(
                4,
                mb_strlen($city->code),
                "Code [{$city->code}] is too long",
            );
        });
    }
}
