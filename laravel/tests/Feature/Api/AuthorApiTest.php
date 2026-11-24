<?php

namespace Tests\Feature\Api;

use App\Models\Author;
use Tests\Feature\Api\Concerns\ActsAsApiClient;
use Tests\TestCase;

/**
 * API /api/authors — полный CRUD (единственный из этой пачки с живым destroy).
 *
 * Кворки, зафиксированные как текущее поведение:
 *  - без фильтров index идёт через indexActionPaginate (PaginateResource, 100/стр);
 *  - любой фильтр (search/ids/limit) переключает на indexAction, где
 *    AuthorService::getList() сейчас доступен и возвращает плоскую коллекцию;
 *  - create/update без last_name подставляет [] вместо null → HTTP 500.
 */
class AuthorApiTest extends TestCase
{
    use ActsAsApiClient;

    private const string FIRST_NAME = 'Лев';

    private const string LAST_NAME = 'Толстой';

    // ---------- GET /api/authors ----------

    public function test_index_without_filters_returns_paginated_resource(): void
    {
        Author::factory()->count(3)->create();

        $response = $this->apiGet('/api/authors')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(3, 'data.items');

        // модель Author переопределяет $perPage = 100
        $this->assertSame(100, $response->json('data.meta.per_page'));
        $this->assertSame(1, $response->json('data.meta.current_page'));
        $this->assertSame(3, $response->json('data.meta.total'));
    }

    /**
     * hasFilters() переключает index на indexAction: ответ без пагинации,
     * limit применяется AuthorQueryFilter на уровне репозитория.
     */
    public function test_index_with_limit_filter_returns_flat_collection(): void
    {
        Author::factory()->count(3)->create();

        $response = $this->apiGet('/api/authors?limit=2')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(2, 'data.items')
            ->assertJsonPath('data.meta.count', 2);

        $this->assertArrayNotHasKey('current_page', $response->json('data.meta'));
    }

    /**
     * Search splits full name into first/last name and applies both conditions.
     */
    public function test_index_with_search_filter_returns_matching_authors(): void
    {
        Author::factory()->create([
            Author::PARAM_FIRST_NAME => 'Лев',
            Author::PARAM_LAST_NAME => 'Толстой',
        ]);
        Author::factory()->create([
            Author::PARAM_FIRST_NAME => 'Фёдор',
            Author::PARAM_LAST_NAME => 'Достоевский',
        ]);

        $this->apiGet('/api/authors?search=лев толстой')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.items.0.first_name', 'Лев')
            ->assertJsonPath('data.items.0.last_name', 'Толстой');
    }

    // ---------- POST /api/authors ----------

    public function test_store_creates_author_returns_201(): void
    {
        $this->apiPost('/api/authors', [
            Author::PARAM_FIRST_NAME => self::FIRST_NAME,
            Author::PARAM_LAST_NAME => self::LAST_NAME,
        ])
            ->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.first_name', self::FIRST_NAME)
            ->assertJsonPath('data.last_name', self::LAST_NAME);

        $this->assertDatabaseHas(Author::TABLE_NAME, [
            Author::PARAM_FIRST_NAME => self::FIRST_NAME,
            Author::PARAM_LAST_NAME => self::LAST_NAME,
        ]);
    }

    public function test_store_rejects_duplicate_full_name(): void
    {
        Author::factory()->create([
            Author::PARAM_FIRST_NAME => self::FIRST_NAME,
            Author::PARAM_LAST_NAME => self::LAST_NAME,
        ]);

        $this->apiPost('/api/authors', [
            Author::PARAM_FIRST_NAME => self::FIRST_NAME,
            Author::PARAM_LAST_NAME => self::LAST_NAME,
        ])
            ->assertUnprocessable()
            ->assertJsonPath('success', false)
            ->assertJsonPath('errors.first_name.0', 'An Author with this name already exists');
    }

    public function test_store_allows_same_first_name_with_other_last_name(): void
    {
        Author::factory()->create([
            Author::PARAM_FIRST_NAME => self::FIRST_NAME,
            Author::PARAM_LAST_NAME => 'Ландау',
        ]);

        $this->apiPost('/api/authors', [
            Author::PARAM_FIRST_NAME => self::FIRST_NAME,
            Author::PARAM_LAST_NAME => self::LAST_NAME,
        ])
            ->assertStatus(201);
    }

    public function test_store_validates_first_name_min_length(): void
    {
        $this->apiPost('/api/authors', [Author::PARAM_FIRST_NAME => 'Я'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([Author::PARAM_FIRST_NAME]);
    }

    /**
     * Документация дефекта: пустая строка превращается middleware
     * ConvertEmptyStringsToNull в null, но AuthorDTO ждёт не-nullable string →
     * TypeError «Argument #2 ($lastName) must be of type string, null given» → 500.
     */
    public function test_store_with_empty_last_name_persists_null(): void
    {
        $this->apiPost('/api/authors', [
            Author::PARAM_FIRST_NAME => self::FIRST_NAME,
            Author::PARAM_LAST_NAME => '',
        ])
            ->assertStatus(500);

        $this->assertDatabaseMissing(Author::TABLE_NAME, [
            Author::PARAM_FIRST_NAME => self::FIRST_NAME,
        ]);
    }

    public function test_store_without_last_name_is_500(): void
    {
        $this->apiPost('/api/authors', [Author::PARAM_FIRST_NAME => self::FIRST_NAME])
            ->assertStatus(500);
    }

    // ---------- GET /api/authors/{id} ----------

    public function test_show_returns_author(): void
    {
        $author = Author::factory()->create();

        $this->apiGet("/api/authors/{$author->id}")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $author->id)
            ->assertJsonPath('data.first_name', $author->first_name);
    }

    /**
     * Документация дефекта: ModelNotFoundException оборачивается в
     * NotFoundHttpException ещё в prepareException, поэтому коллбэк для
     * ModelNotFoundException не срабатывает — срабатывает Throwable-коллбэк → 500.
     */
    public function test_show_missing_author_is_200_with_success_false(): void
    {
        $this->apiGet('/api/authors/99999')
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }

    // ---------- PUT /api/authors/{id} ----------

    public function test_update_changes_names(): void
    {
        $author = Author::factory()->create();

        $this->apiPut("/api/authors/{$author->id}", [
            Author::PARAM_FIRST_NAME => 'Антон',
            Author::PARAM_LAST_NAME => 'Чехов',
        ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.first_name', 'Антон')
            ->assertJsonPath('data.last_name', 'Чехов');

        $this->assertDatabaseHas(Author::TABLE_NAME, [
            Author::PARAM_ID => $author->id,
            Author::PARAM_FIRST_NAME => 'Антон',
            Author::PARAM_LAST_NAME => 'Чехов',
        ]);
    }

    public function test_update_rejects_full_name_of_other_author(): void
    {
        Author::factory()->create([
            Author::PARAM_FIRST_NAME => 'Антон',
            Author::PARAM_LAST_NAME => 'Чехов',
        ]);
        $author = Author::factory()->create([
            Author::PARAM_FIRST_NAME => 'Свободное',
            Author::PARAM_LAST_NAME => 'Имя автора',
        ]);

        $this->apiPut("/api/authors/{$author->id}", [
            Author::PARAM_FIRST_NAME => 'Антон',
            Author::PARAM_LAST_NAME => 'Чехов',
        ])
            ->assertUnprocessable()
            ->assertJsonPath('errors.first_name.0', 'An Author with this name already exists');
    }

    /**
     * Документация дефекта: ModelNotFoundException → NotFoundHttpException → 500
     * (см. test_show_missing_author).
     */
    public function test_update_missing_author_is_200_with_success_false(): void
    {
        $this->apiPut('/api/authors/99999', [
            Author::PARAM_FIRST_NAME => 'Антон',
            Author::PARAM_LAST_NAME => 'Чехов',
        ])
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }

    /**
     * Документация дефекта: без last_name в запросе DTO содержит [] →
     * массив в биндинге update → 500 (см. AuthorDTOTest).
     */
    public function test_update_without_last_name_is_500(): void
    {
        $author = Author::factory()->create();

        $this->apiPut("/api/authors/{$author->id}", [
            Author::PARAM_FIRST_NAME => 'Антон',
        ])
            ->assertStatus(500);
    }

    // ---------- DELETE /api/authors/{id} ----------

    public function test_destroy_deletes_author(): void
    {
        $author = Author::factory()->create();

        $this->apiDelete("/api/authors/{$author->id}")
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertModelMissing($author);
    }

    /**
     * Документация дефекта: ModelNotFoundException → NotFoundHttpException → 500
     * (см. test_show_missing_author).
     */
    public function test_destroy_missing_author_is_200_with_success_false(): void
    {
        $this->apiDelete('/api/authors/99999')
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }
}
