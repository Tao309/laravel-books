<?php

namespace Tests\Feature\CQRS;

use App\CQRS\Bus;
use App\CQRS\Commands\Books\CreateBookCommand;
use App\CQRS\Commands\Books\DestroyBookCommand;
use App\CQRS\Commands\Books\UpdateBookCommand;
use App\CQRS\Queries\Books\GetBookByIdQuery;
use App\CQRS\Queries\Books\GetBooksPaginateQuery;
use App\CQRS\Queries\Books\GetBooksQuery;
use App\DTOs\BookDTO;
use App\DTOs\CollectionQueryDto;
use App\Models\Author;
use App\Models\BindingType;
use App\Models\Book;
use App\Models\BookSeries;
use App\Models\BookUserData;
use App\Models\PaperType;
use App\Models\PublishingBrand;
use App\Models\PublishingHouse;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * Сквозной путь команды/запроса через реальный контейнер и репозиторий.
 * У Book полный набор привязок в BookProvider — все 6 хендлеров
 * разрешаются, сьют полностью зелёный (как у Authors).
 *
 * Фабрика Book гнездит пять справочников со случайными уникальными
 * значениями — для нескольких книг в тесте все FK задаём явно через
 * общие lazy-справочники (по одному на тест).
 */
class BooksBusTest extends TestCase
{
    use RefreshDatabase;

    private Bus $bus;

    private BindingType $bindingType;

    private PaperType $paperType;

    private PublishingHouse $publishingHouse;

    private PublishingBrand $publishingBrand;

    private BookSeries $bookSeries;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());

        $this->bus = $this->app->make(Bus::class);
    }

    private function bindingType(): BindingType
    {
        return $this->bindingType ??= BindingType::factory()->create();
    }

    private function paperType(): PaperType
    {
        return $this->paperType ??= PaperType::factory()->create();
    }

    private function publishingHouse(): PublishingHouse
    {
        return $this->publishingHouse ??= PublishingHouse::factory()->create();
    }

    private function publishingBrand(): PublishingBrand
    {
        return $this->publishingBrand ??= PublishingBrand::factory()->create();
    }

    private function bookSeries(): BookSeries
    {
        return $this->bookSeries ??= BookSeries::factory()->create();
    }

    private function dto(array $overrides = []): BookDTO
    {
        return BookDTO::fromArray(array_merge([
            Book::PARAM_TITLE => 'Книга через шину',
            Book::PARAM_PUBLISH_YEAR => '2020',
            Book::PARAM_BINDING_TYPE => [BindingType::PARAM_ID => $this->bindingType()->id],
            Book::PARAM_AUTHORS => [[Author::PARAM_ID => Author::factory()->create()->id]],
        ], $overrides));
    }

    private function factoryBook(): Book
    {
        return Book::factory()->create([
            Book::PARAM_BINDING_TYPE_ID => $this->bindingType()->id,
            Book::PARAM_PAPER_TYPE_ID => $this->paperType()->id,
            Book::PARAM_PUBLISHING_HOUSE_ID => $this->publishingHouse()->id,
            Book::PARAM_PUBLISHING_BRAND_ID => $this->publishingBrand()->id,
            Book::PARAM_BOOK_SERIES_ID => $this->bookSeries()->id,
        ]);
    }

    public function test_create_command_persists_book_with_author_and_user_data(): void
    {
        $book = $this->bus->handle(new CreateBookCommand($this->dto()));

        $this->assertInstanceOf(Book::class, $book);
        $this->assertDatabaseHas(Book::TABLE_NAME, [
            Book::PARAM_ID => $book->id,
            Book::PARAM_TITLE => 'Книга через шину',
            Book::PARAM_PUBLISH_YEAR => '2020',
            Book::PARAM_BINDING_TYPE_ID => $this->bindingType()->id,
            Book::PARAM_USER_CREATED_ID => Auth::id(),
        ]);

        $this->assertNotNull($book->authors->first());

        $this->assertDatabaseCount(BookUserData::TABLE_NAME, 1);
        $this->assertDatabaseHas(BookUserData::TABLE_NAME, [
            BookUserData::PARAM_BOOK_ID => $book->id,
            BookUserData::PARAM_USER_ID => Auth::id(),
        ]);
    }

    public function test_update_command_changes_title_and_syncs_authors(): void
    {
        $book = $this->bus->handle(new CreateBookCommand($this->dto()));

        $secondAuthor = Author::factory()->create();

        $updated = $this->bus->handle(new UpdateBookCommand($book->id, $this->dto([
            Book::PARAM_TITLE => 'Обновлённая через шину',
            Book::PARAM_AUTHORS => [[Author::PARAM_ID => $secondAuthor->id]],
        ])));

        $this->assertSame('Обновлённая через шину', $updated->title);
        $this->assertSame(
            [$secondAuthor->id],
            $updated->authors->pluck(Author::PARAM_ID)->all(),
        );

        // budData пуст — BUD не пересоздаётся
        $this->assertDatabaseCount(BookUserData::TABLE_NAME, 1);
    }

    public function test_destroy_command_deletes_book(): void
    {
        $book = $this->bus->handle(new CreateBookCommand($this->dto()));

        $this->assertTrue($this->bus->handle(new DestroyBookCommand($book->id)));

        $this->assertDatabaseMissing(Book::TABLE_NAME, [Book::PARAM_ID => $book->id]);

        // book_user_datas удаляются каскадом на уровне БД
        $this->assertDatabaseCount(BookUserData::TABLE_NAME, 0);
    }

    public function test_destroy_command_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->bus->handle(new DestroyBookCommand(99999));
    }

    public function test_get_books_query_returns_all(): void
    {
        $first = $this->factoryBook();
        $second = $this->factoryBook();

        $result = $this->bus->handle(new GetBooksQuery(new CollectionQueryDto));

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEqualsCanonicalizing(
            [$first->id, $second->id],
            $result->pluck(Book::PARAM_ID)->all(),
        );
    }

    public function test_get_books_paginate_query_returns_paginator_with_per_page_100(): void
    {
        for ($i = 0; $i < 18; $i++) {
            $this->factoryBook();
        }

        $result = $this->bus->handle(new GetBooksPaginateQuery(new CollectionQueryDto));

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertSame(18, $result->total());
        $this->assertSame(100, $result->perPage());
        $this->assertSame(18, $result->count());
    }

    public function test_get_by_id_query_returns_model(): void
    {
        $book = $this->factoryBook();

        $this->assertTrue(
            $this->bus->handle(new GetBookByIdQuery($book->id))->is($book),
        );
    }

    public function test_get_by_id_query_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->bus->handle(new GetBookByIdQuery(99999));
    }
}
