<?php

namespace Tests\Feature\Api;

use App\Models\Author;
use App\Models\BindingType;
use App\Models\Book;
use App\Models\BookSeries;
use App\Models\BookUserData;
use App\Models\PaperType;
use App\Models\PublishingBrand;
use App\Models\PublishingHouse;
use Tests\Feature\Api\Concerns\ActsAsApiClient;
use Tests\TestCase;

/**
 * API /api/books — полный CRUD (все 5 action'ов живы: BookProvider
 * биндит все хендлеры, destroy-роут зарегистрирован).
 *
 * Основное покрытие — правила StoreBookRequest / UpdateBookRequest /
 * AbstractBookRequest: required-ключи (title, publish_year,
 * binding_type, authors), уникальность title/isbn на уровне правил
 * (в БД уникальных индексов на books нет), integer:strict,
 * BookRatingRule, book_user_data (release_date формат Y.m.d — но см.
 * characterization-тест: DTO парсит Y-m-d H:i:s → 500).
 *
 * Контекст: Book::$perPage = 100; search-фильтр IndexRequest —
 * min:3 / max:20, отбор идёт по LIKE LOWER(title) с limit 7 внутри
 * фильтра (paginate() его переопределяет).
 */
class BookApiTest extends TestCase
{
    use ActsAsApiClient;

    private BindingType $bindingType;

    private Author $author;

    private PaperType $paperType;

    private PublishingHouse $publishingHouse;

    private PublishingBrand $publishingBrand;

    private BookSeries $bookSeries;

    private function bindingType(): BindingType
    {
        return $this->bindingType ??= BindingType::factory()->create();
    }

    private function author(): Author
    {
        return $this->author ??= Author::factory()->create();
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

    private function payload(array $overrides = []): array
    {
        return array_merge([
            Book::PARAM_TITLE => 'Мастер и Маргарита',
            Book::PARAM_PUBLISH_YEAR => 1967,
            Book::PARAM_BINDING_TYPE => [BindingType::PARAM_ID => $this->bindingType()->id],
            Book::PARAM_AUTHORS => [[Author::PARAM_ID => $this->author()->id]],
        ], $overrides);
    }

    private function factoryBook(array $overrides = []): Book
    {
        return Book::factory()->create(array_merge([
            Book::PARAM_BINDING_TYPE_ID => $this->bindingType()->id,
            Book::PARAM_PAPER_TYPE_ID => $this->paperType()->id,
            Book::PARAM_PUBLISHING_HOUSE_ID => $this->publishingHouse()->id,
            Book::PARAM_PUBLISHING_BRAND_ID => $this->publishingBrand()->id,
            Book::PARAM_BOOK_SERIES_ID => $this->bookSeries()->id,
        ], $overrides));
    }

    // ---------- GET /api/books ----------

    public function test_index_without_filters_returns_paginated_resource(): void
    {
        $this->factoryBook();
        $this->factoryBook();
        $this->factoryBook();

        $response = $this->apiGet('/api/books')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(3, 'data.items');

        // модель Book переопределяет $perPage = 100
        $this->assertSame(100, $response->json('data.meta.per_page'));
        $this->assertSame(3, $response->json('data.meta.total'));
    }

    public function test_index_with_search_filter_returns_matching_books(): void
    {
        $this->factoryBook([Book::PARAM_TITLE => 'Codex Astartes']);
        $this->factoryBook([Book::PARAM_TITLE => 'Мастер и Маргарита']);

        // у BookController::index нет ветки hasFilters — search всегда
        // проходит через пагинат (в отличие от Authors, где это 500)
        $this->apiGet('/api/books?search=codex')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.items.0.title', 'Codex Astartes');
    }

    // ---------- POST /api/books ----------

    public function test_store_creates_book_returns_201(): void
    {
        $this->apiPost('/api/books', $this->payload())
            ->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'Мастер и Маргарита')
            ->assertJsonPath('data.publish_year', 1967);

        $this->assertDatabaseHas(Book::TABLE_NAME, [
            Book::PARAM_TITLE => 'Мастер и Маргарита',
            Book::PARAM_BINDING_TYPE_ID => $this->bindingType()->id,
        ]);

        // create() всегда создаёт BookUserData (пустой, budData = [])
        $this->assertDatabaseCount(BookUserData::TABLE_NAME, 1);
    }

    public function test_store_saves_optional_scalars(): void
    {
        $this->apiPost('/api/books', $this->payload([
            Book::PARAM_ISBN => '9785389079618',
            Book::PARAM_PAGES => 480,
            Book::PARAM_CIRCULATION => 2500,
            Book::PARAM_SIZE => '84x108/32',
            Book::PARAM_LIVELIB_RATING => 4.5,
        ]))
            ->assertStatus(201);

        $this->assertDatabaseHas(Book::TABLE_NAME, [
            Book::PARAM_ISBN => '9785389079618',
            Book::PARAM_PAGES => 480,
            Book::PARAM_CIRCULATION => 2500,
            Book::PARAM_SIZE => '84x108/32',
            Book::PARAM_LIVELIB_RATING => 4.5,
        ]);
    }

    public function test_store_saves_book_user_data_without_release_date(): void
    {
        $this->apiPost('/api/books', $this->payload([
            Book::PARAM_BOOK_USER_DATA => [
                BookUserData::PARAM_COMMENT => 'Читал в 2020',
            ],
        ]))
            ->assertStatus(201);

        $this->assertDatabaseHas(BookUserData::TABLE_NAME, [
            BookUserData::PARAM_COMMENT => 'Читал в 2020',
        ]);
    }

    /**
     * Документация дефекта: валидация StoreBookRequest/UpdateBookRequest
     * допускает release_date в формате 'Y.m.d', но
     * BookUserDataDTO::getEntityData() парсит дату через
     * DateService::createFromDate() с форматом по умолчанию
     * 'Y-m-d H:i:s' → InvalidFormatException → HTTP 500, книга не
     * создаётся (rollback в BookRepository::create). Та же нестыковка
     * форматов дат, что и у product_user_data (см. ProductApiTest).
     * Если формат в DTO или реквесте унифицируют — перевернуть тест
     * на 201 + строку в book_user_datas.
     */
    public function test_store_with_release_date_in_rule_format_is_500(): void
    {
        $this->apiPost('/api/books', $this->payload([
            Book::PARAM_ISBN => '9785389079618',
            Book::PARAM_BOOK_USER_DATA => [
                BookUserData::PARAM_RELEASE_DATE => '2026.01.15',
                BookUserData::PARAM_COMMENT => 'Читал в 2020',
            ],
        ]))
            ->assertStatus(500)
            ->assertJsonPath('success', false);

        // транзакция откатилась — книги нет
        $this->assertDatabaseMissing(Book::TABLE_NAME, [
            Book::PARAM_ISBN => '9785389079618',
        ]);
    }

    public function test_store_rejects_duplicate_title(): void
    {
        $this->factoryBook([Book::PARAM_TITLE => 'Мастер и Маргарита']);

        $this->apiPost('/api/books', $this->payload())
            ->assertUnprocessable()
            ->assertJsonPath('success', false)
            ->assertJsonPath(
                'errors.' . Book::PARAM_TITLE . '.0',
                'A Book with this title already exists',
            );
    }

    public function test_store_rejects_duplicate_isbn(): void
    {
        $this->factoryBook([Book::PARAM_ISBN => '9785389079618']);

        $this->apiPost('/api/books', $this->payload([
            Book::PARAM_ISBN => '9785389079618',
        ]))
            ->assertUnprocessable()
            ->assertJsonPath(
                'errors.' . Book::PARAM_ISBN . '.0',
                'A Book with this ISBN already exists',
            );
    }

    public function test_store_validates_title_min_length(): void
    {
        $this->apiPost('/api/books', $this->payload([
            Book::PARAM_TITLE => 'Я',
        ]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors([Book::PARAM_TITLE]);
    }

    public function test_store_requires_binding_type_and_authors(): void
    {
        $this->apiPost('/api/books', $this->payload([
            Book::PARAM_BINDING_TYPE => null,
            Book::PARAM_AUTHORS => null,
        ]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors([Book::PARAM_BINDING_TYPE, Book::PARAM_AUTHORS]);
    }

    public function test_store_requires_publish_year(): void
    {
        $payload = $this->payload();
        unset($payload[Book::PARAM_PUBLISH_YEAR]);

        $this->apiPost('/api/books', $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([Book::PARAM_PUBLISH_YEAR]);
    }

    public function test_store_rejects_unknown_binding_type_id(): void
    {
        $this->apiPost('/api/books', $this->payload([
            Book::PARAM_BINDING_TYPE => [BindingType::PARAM_ID => 99999],
        ]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors([Book::PARAM_BINDING_TYPE]);
    }

    public function test_store_rejects_unknown_author_id(): void
    {
        $this->apiPost('/api/books', $this->payload([
            Book::PARAM_AUTHORS => [[Author::PARAM_ID => 99999]],
        ]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['authors.0.id']);
    }

    /**
     * integer:strict — строковые цифры не проходят.
     */
    public function test_store_rejects_string_pages(): void
    {
        $this->apiPost('/api/books', $this->payload([
            Book::PARAM_PAGES => '480',
        ]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors([Book::PARAM_PAGES]);
    }

    /**
     * BookRatingRule: рейтинг должен быть float/int — строка '4.5' не проходит.
     */
    public function test_store_rejects_string_rating(): void
    {
        $this->apiPost('/api/books', $this->payload([
            Book::PARAM_LIVELIB_RATING => '4.5',
        ]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors([Book::PARAM_LIVELIB_RATING]);
    }

    public function test_store_rejects_wrong_book_user_data_date_format(): void
    {
        $this->apiPost('/api/books', $this->payload([
            Book::PARAM_BOOK_USER_DATA => [
                BookUserData::PARAM_RELEASE_DATE => '2026-01-15',
            ],
        ]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors([Book::PARAM_BOOK_USER_DATA . '.' . BookUserData::PARAM_RELEASE_DATE]);
    }

    // ---------- GET /api/books/{id} ----------

    public function test_show_returns_book(): void
    {
        $book = $this->factoryBook();

        $this->apiGet("/api/books/{$book->id}")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $book->id)
            ->assertJsonPath('data.title', $book->title);
    }

    /**
     * Документация дефекта: ModelNotFoundException оборачивается в
     * NotFoundHttpException ещё в prepareException — Throwable-коллбэк
     * рендерит 500 (то же поведение, что у Authors, см. AuthorApiTest).
     */
    public function test_show_missing_book_is_500_with_success_false(): void
    {
        $this->apiGet('/api/books/99999')
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }

    // ---------- PUT /api/books/{id} ----------

    public function test_update_changes_title(): void
    {
        $book = $this->factoryBook();

        $this->apiPut("/api/books/{$book->id}", $this->payload())
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'Мастер и Маргарита');

        $this->assertDatabaseHas(Book::TABLE_NAME, [
            Book::PARAM_ID => $book->id,
            Book::PARAM_TITLE => 'Мастер и Маргарита',
        ]);
    }

    public function test_update_rejects_title_of_other_book(): void
    {
        $this->factoryBook([Book::PARAM_TITLE => 'Занятый тайтл']);

        $book = $this->factoryBook([Book::PARAM_TITLE => 'Свободный тайтл']);

        $this->apiPut("/api/books/{$book->id}", $this->payload([
            Book::PARAM_TITLE => 'Занятый тайтл',
        ]))
            ->assertUnprocessable()
            ->assertJsonPath(
                'errors.' . Book::PARAM_TITLE . '.0',
                'A Book with this title already exists',
            );
    }

    public function test_update_allows_own_title(): void
    {
        $book = $this->factoryBook([Book::PARAM_TITLE => 'Мастер и Маргарита']);

        // unique->ignore($id): свой тайтл проходит
        $this->apiPut("/api/books/{$book->id}", $this->payload())
            ->assertOk();
    }

    public function test_update_without_title_is_unprocessable(): void
    {
        $book = $this->factoryBook();

        $payload = $this->payload();
        unset($payload[Book::PARAM_TITLE]);

        $this->apiPut("/api/books/{$book->id}", $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([Book::PARAM_TITLE]);
    }

    /**
     * Документация дефекта: ModelNotFoundException → NotFoundHttpException → 500
     * (см. test_show_missing_book).
     */
    public function test_update_missing_book_is_500_with_success_false(): void
    {
        $this->apiPut('/api/books/99999', $this->payload())
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }

    // ---------- DELETE /api/books/{id} ----------

    public function test_destroy_deletes_book(): void
    {
        $book = $this->factoryBook();

        $this->apiDelete("/api/books/{$book->id}")
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertModelMissing($book);

        // book_user_datas удаляются каскадом на уровне БД
        $this->assertDatabaseCount(BookUserData::TABLE_NAME, 0);
    }

    /**
     * Документация дефекта: ModelNotFoundException → NotFoundHttpException → 500
     * (см. test_show_missing_book).
     */
    public function test_destroy_missing_book_is_500_with_success_false(): void
    {
        $this->apiDelete('/api/books/99999')
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }
}
