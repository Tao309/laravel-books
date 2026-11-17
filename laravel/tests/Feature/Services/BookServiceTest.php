<?php

namespace Tests\Feature\Services;

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
use App\Services\BookService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * BookService: обёртки над Bus (GetBooksQuery / GetBooksPaginateQuery /
 * GetBookByIdQuery / Create-Update-DestroyBookCommand → BookRepository).
 * Фабрика Book гнездит пять справочников со случайными уникальными
 * значениями — для двух книг в тесте все FK задаём явно.
 */
class BookServiceTest extends TestCase
{
    use RefreshDatabase;

    private BookService $service;

    private BindingType $bindingType;

    private Author $author;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());

        $this->service = $this->app->make(BookService::class);
    }

    private function bindingType(): BindingType
    {
        return $this->bindingType ??= BindingType::factory()->create();
    }

    private function author(): Author
    {
        return $this->author ??= Author::factory()->create();
    }

    private function dto(array $overrides = []): BookDTO
    {
        return BookDTO::fromArray(array_merge([
            Book::PARAM_TITLE => 'Книга для сервиса',
            Book::PARAM_PUBLISH_YEAR => '2020',
            Book::PARAM_BINDING_TYPE => [BindingType::PARAM_ID => $this->bindingType()->id],
            Book::PARAM_AUTHORS => [[Author::PARAM_ID => $this->author()->id]],
        ], $overrides));
    }

    private function factoryBook(): Book
    {
        return Book::factory()->create([
            Book::PARAM_BINDING_TYPE_ID => $this->bindingType()->id,
            Book::PARAM_PAPER_TYPE_ID => PaperType::factory()->create()->id,
            Book::PARAM_PUBLISHING_HOUSE_ID => PublishingHouse::factory()->create()->id,
            Book::PARAM_PUBLISHING_BRAND_ID => PublishingBrand::factory()->create()->id,
            Book::PARAM_BOOK_SERIES_ID => BookSeries::factory()->create()->id,
        ]);
    }

    public function test_get_list_returns_all_books(): void
    {
        $first = $this->factoryBook();
        $second = $this->factoryBook();

        $result = $this->service->getList(new CollectionQueryDto);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEqualsCanonicalizing(
            [$first->id, $second->id],
            $result->pluck(Book::PARAM_ID)->all(),
        );
    }

    public function test_get_list_paginate_returns_paginator(): void
    {
        $this->factoryBook();
        $this->factoryBook();

        $result = $this->service->getListPaginate(new CollectionQueryDto);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertSame(2, $result->total());
        $this->assertSame(2, $result->count());
        // per_page берётся из CollectionQueryDto (дефолт 100), как у Authors
        $this->assertSame(100, $result->perPage());
    }

    public function test_get_by_id_returns_book(): void
    {
        $book = $this->factoryBook();

        $this->assertTrue($this->service->getById($book->id)->is($book));
    }

    public function test_get_by_id_missing_throws_model_not_found(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->service->getById(99999);
    }

    public function test_create_persists_book_with_author_and_user_data(): void
    {
        $result = $this->service->create($this->dto());

        $this->assertDatabaseHas(Book::TABLE_NAME, [
            Book::PARAM_ID => $result->id,
            Book::PARAM_TITLE => 'Книга для сервиса',
            Book::PARAM_PUBLISH_YEAR => '2020',
            Book::PARAM_BINDING_TYPE_ID => $this->bindingType()->id,
            Book::PARAM_USER_CREATED_ID => Auth::id(),
        ]);

        $this->assertSame(
            [$this->author()->id],
            $result->authors->pluck(Author::PARAM_ID)->all(),
        );

        $this->assertDatabaseCount(BookUserData::TABLE_NAME, 1);
        $this->assertDatabaseHas(BookUserData::TABLE_NAME, [
            BookUserData::PARAM_BOOK_ID => $result->id,
            BookUserData::PARAM_USER_ID => Auth::id(),
        ]);
    }

    public function test_update_by_id_changes_title_and_syncs_authors(): void
    {
        $book = $this->service->create($this->dto());

        $secondAuthor = Author::factory()->create();

        $result = $this->service->updateById($book->id, $this->dto([
            Book::PARAM_TITLE => 'Обновлённая книга',
            Book::PARAM_AUTHORS => [[Author::PARAM_ID => $secondAuthor->id]],
        ]));

        $this->assertSame('Обновлённая книга', $result->title);
        $this->assertSame(
            [$secondAuthor->id],
            $result->authors->pluck(Author::PARAM_ID)->all(),
        );

        // budData пуст — BUD не трогается
        $this->assertDatabaseCount(BookUserData::TABLE_NAME, 1);
    }

    public function test_delete_by_id_removes_book_and_user_data(): void
    {
        $book = $this->service->create($this->dto());

        $this->assertTrue($this->service->deleteById($book->id));

        $this->assertDatabaseMissing(Book::TABLE_NAME, [Book::PARAM_ID => $book->id]);
        $this->assertDatabaseCount(BookUserData::TABLE_NAME, 0);
    }
}
