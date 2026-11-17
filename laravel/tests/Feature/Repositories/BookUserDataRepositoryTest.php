<?php

namespace Tests\Feature\Repositories;

use App\DTOs\BookUserDataDTO;
use App\Models\Book;
use App\Models\BookUserData;
use App\Models\User;
use App\Repositories\BookUserDataRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Репозиторий BookUserData скоупится по Auth::id() на каждой операции:
 * чужие записи недоступны. get() — firstOrFail.
 */
class BookUserDataRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private BookUserDataRepository $repository;

    private User $user;

    private Book $book;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(BookUserDataRepository::class);
        $this->user = User::factory()->create();
        $this->book = Book::factory()->create();

        $this->actingAs($this->user);
    }

    private function dto(array $overrides = []): BookUserDataDTO
    {
        return new BookUserDataDTO(
            bookId: $this->book->id,
            releaseDate: $overrides['release_date'] ?? '2026-11-10 12:00:00',
            listenPriceValue: $overrides['listen_price_value'] ?? 1500,
            comment: $overrides['comment'] ?? 'Дождаться скидки',
        );
    }

    public function test_get_returns_row_for_current_user(): void
    {
        $userData = BookUserData::factory()->create([
            BookUserData::PARAM_BOOK_ID => $this->book->id,
            BookUserData::PARAM_USER_ID => $this->user->id,
        ]);

        $found = $this->repository->get($this->book->id);

        // Model::is() неприменим: составной PK роняет getKey() —
        // сравниваем по ключевой паре.
        $this->assertSame($userData->user_id, $found->user_id);
        $this->assertSame($userData->book_id, $found->book_id);
    }

    public function test_get_throws_for_missing_row(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->get($this->book->id);
    }

    public function test_get_is_scoped_to_current_user(): void
    {
        BookUserData::factory()->create([
            BookUserData::PARAM_BOOK_ID => $this->book->id,
            BookUserData::PARAM_USER_ID => User::factory()->create()->id,
        ]);

        $this->expectException(ModelNotFoundException::class);

        $this->repository->get($this->book->id);
    }

    public function test_create_persists_row_with_current_user(): void
    {
        $created = $this->repository->create($this->book->id, $this->dto());

        $this->assertDatabaseHas(BookUserData::TABLE_NAME, [
            BookUserData::PARAM_BOOK_ID => $this->book->id,
            BookUserData::PARAM_USER_ID => $this->user->id,
            BookUserData::PARAM_LISTEN_PRICE_VALUE => 1500,
            BookUserData::PARAM_COMMENT => 'Дождаться скидки',
        ]);
        $this->assertSame($this->user->id, $created->user_id);
    }

    public function test_update_changes_fields_and_returns_affected_rows(): void
    {
        BookUserData::factory()->create([
            BookUserData::PARAM_BOOK_ID => $this->book->id,
            BookUserData::PARAM_USER_ID => $this->user->id,
        ]);

        $affected = $this->repository->update($this->book->id, $this->dto([
            'listen_price_value' => 2000,
        ]));

        $this->assertSame(1, $affected);
        $this->assertDatabaseHas(BookUserData::TABLE_NAME, [
            BookUserData::PARAM_BOOK_ID => $this->book->id,
            BookUserData::PARAM_LISTEN_PRICE_VALUE => 2000,
        ]);
    }

    public function test_update_missing_row_returns_zero(): void
    {
        $this->assertSame(0, $this->repository->update($this->book->id, $this->dto()));
    }

    public function test_delete_removes_row_of_current_user(): void
    {
        BookUserData::factory()->create([
            BookUserData::PARAM_BOOK_ID => $this->book->id,
            BookUserData::PARAM_USER_ID => $this->user->id,
        ]);

        $this->repository->delete($this->book->id);

        // Книга создана до actingAs(), поэтому BookFactory::afterCreating()
        // успел создать строку для владельца — проверяем удаление только
        // пары (book_id, user_id), а не общий счётчик таблицы.
        $this->assertDatabaseMissing(BookUserData::TABLE_NAME, [
            BookUserData::PARAM_BOOK_ID => $this->book->id,
            BookUserData::PARAM_USER_ID => $this->user->id,
        ]);
    }

    /**
     * Документация текущего поведения: delete() не сигналит об отсутствии
     * строки — чужая/несуществующая запись просто игнорируется.
     */
    public function test_delete_of_other_users_row_is_silent_noop(): void
    {
        $others = BookUserData::factory()->create([
            BookUserData::PARAM_BOOK_ID => $this->book->id,
            BookUserData::PARAM_USER_ID => User::factory()->create()->id,
        ]);

        $this->repository->delete($this->book->id);

        // assertModelExists неприменим: составной PK роняет getKey().
        $this->assertDatabaseHas(BookUserData::TABLE_NAME, [
            BookUserData::PARAM_BOOK_ID => $others->book_id,
            BookUserData::PARAM_USER_ID => $others->user_id,
        ]);
    }
}
