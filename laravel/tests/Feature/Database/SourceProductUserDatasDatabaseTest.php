<?php

namespace Tests\Feature\Database;

use App\Models\SourceProduct;
use App\Models\SourceProductUserData;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Database-тесты: таблица source_product_user_data на реальном pgsql —
 * составной PK и каскадные FK.
 */
class SourceProductUserDatasDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_table_has_expected_columns(): void
    {
        $columns = Schema::getColumnListing(SourceProductUserData::TABLE_NAME);

        $this->assertContains(SourceProductUserData::PARAM_SOURCE_PRODUCT_ID, $columns);
        $this->assertContains(SourceProductUserData::PARAM_USER_ID, $columns);
        $this->assertContains(SourceProductUserData::PARAM_LISTEN_PRICE_VALUE, $columns);
        $this->assertContains(SourceProductUserData::PARAM_COMMENT, $columns);
        $this->assertContains('created_at', $columns);
        $this->assertContains('updated_at', $columns);
    }

    public function test_database_enforces_unique_source_product_user_pair(): void
    {
        $userData = SourceProductUserData::factory()->create();

        $this->expectException(UniqueConstraintViolationException::class);

        SourceProductUserData::factory()->create([
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $userData->source_product_id,
            SourceProductUserData::PARAM_USER_ID => $userData->user_id,
        ]);
    }

    public function test_deleting_source_product_cascades_to_user_data(): void
    {
        $userData = SourceProductUserData::factory()->create();

        // связи на SourceProduct у модели нет (только дефектный book())
        SourceProduct::query()->find($userData->source_product_id)?->delete();

        // assertModelMissing неприменим: составной PK роняет getKey().
        $this->assertDatabaseCount(SourceProductUserData::TABLE_NAME, 0);
    }

    public function test_deleting_user_cascades_to_user_data(): void
    {
        $userData = SourceProductUserData::factory()->create();

        User::query()->find($userData->user_id)?->delete();

        // Документация текущего поведения: каскад по user_id настроен
        // (cascadeOnDelete в миграции) и строка удалённого пользователя
        // исчезает. НО SourceProductFactory::afterCreating() создаёт вторую
        // строку для владельца (Auth::id() ?? user_created_id — другой
        // пользователь): счётчик не 0, а 1.
        $this->assertDatabaseCount(SourceProductUserData::TABLE_NAME, 1);
    }
}
