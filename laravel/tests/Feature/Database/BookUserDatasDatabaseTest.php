<?php

namespace Tests\Feature\Database;

use App\Models\Book;
use App\Models\BookUserData;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Database-тесты: таблица book_user_data на реальном pgsql —
 * составной PK (book_id, user_id) и каскадные FK.
 */
class BookUserDatasDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_table_has_expected_columns(): void
    {
        $columns = Schema::getColumnListing(BookUserData::TABLE_NAME);

        $this->assertContains(BookUserData::PARAM_BOOK_ID, $columns);
        $this->assertContains(BookUserData::PARAM_USER_ID, $columns);
        $this->assertContains(BookUserData::PARAM_RELEASE_DATE, $columns);
        $this->assertContains(BookUserData::PARAM_LISTEN_PRICE_VALUE, $columns);
        $this->assertContains(BookUserData::PARAM_COMMENT, $columns);
        $this->assertContains('created_at', $columns);
        $this->assertContains('updated_at', $columns);
    }

    public function test_listen_price_value_column_is_integer(): void
    {
        // pgsql возвращает native doctrine-типы: int4, а не integer
        $this->assertSame(
            'int4',
            Schema::getColumnType(BookUserData::TABLE_NAME, BookUserData::PARAM_LISTEN_PRICE_VALUE),
        );
    }

    public function test_database_enforces_unique_book_user_pair(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        BookUserData::factory()->create([
            BookUserData::PARAM_BOOK_ID => $book->id,
            BookUserData::PARAM_USER_ID => $user->id,
        ]);

        $this->expectException(UniqueConstraintViolationException::class);

        BookUserData::factory()->create([
            BookUserData::PARAM_BOOK_ID => $book->id,
            BookUserData::PARAM_USER_ID => $user->id,
        ]);
    }

    public function test_same_book_for_different_users_is_allowed(): void
    {
        // BookFactory::afterCreating() дополнительно создаёт bookUserData
        // для владельца книги (Auth::id() ?? user_created_id) — поэтому
        // общий счётчик строк не равен 2; проверяем только наши пары.
        $book = Book::factory()->create();

        $firstUser = User::factory()->create();
        $secondUser = User::factory()->create();

        BookUserData::factory()->create([
            BookUserData::PARAM_BOOK_ID => $book->id,
            BookUserData::PARAM_USER_ID => $firstUser->id,
        ]);
        BookUserData::factory()->create([
            BookUserData::PARAM_BOOK_ID => $book->id,
            BookUserData::PARAM_USER_ID => $secondUser->id,
        ]);

        $this->assertSame(2, BookUserData::query()
            ->whereIn(BookUserData::PARAM_USER_ID, [$firstUser->id, $secondUser->id])
            ->count());
    }

    public function test_deleting_book_cascades_to_user_data(): void
    {
        $userData = BookUserData::factory()->create();

        $userData->book->delete();

        // assertModelMissing неприменим: составной PK (book_id, user_id) роняет
        // getKey() TypeError'ом; счётчик строк полностью фиксирует каскад.
        $this->assertDatabaseCount(BookUserData::TABLE_NAME, 0);
    }

    public function test_deleting_user_cascades_to_user_data(): void
    {
        $userData = BookUserData::factory()->create();

        // связи user() у модели нет — удаляем по id
        User::query()->find($userData->user_id)?->delete();

        // Документация текущего поведения: каскад по user_id настроен
        // (cascadeOnDelete в миграции) и строка удалённого пользователя
        // исчезает. НО BookFactory::afterCreating() создаёт вторую строку
        // для владельца книги (Auth::id() ?? user_created_id — другой
        // пользователь): счётчик не 0, а 1.
        $this->assertDatabaseCount(BookUserData::TABLE_NAME, 1);
    }
}
