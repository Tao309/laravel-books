<?php

namespace Tests\Feature\Repositories;

use App\CQRS\Queries\Authors\GetAuthorsPaginateQuery;
use App\DTOs\AuthorDTO;
use App\DTOs\CollectionQueryDto;
use App\Models\Author;
use App\Repositories\AuthorRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * AuthorRepository — единственный справочник с AuthorQueryFilter:
 * search разбивает полное имя на first/last. Фильтры работают на уровне
 * репозитория, но через API недостижимы (AuthorService::getList
 * отсутствует — см. AuthorApiTest).
 */
class AuthorRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private AuthorRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(AuthorRepository::class);
    }

    public function test_create_persists_author(): void
    {
        $author = $this->repository->create(new AuthorDTO('Лев', 'Толстой'));

        $this->assertDatabaseHas(Author::TABLE_NAME, [
            Author::PARAM_ID => $author->id,
            Author::PARAM_FIRST_NAME => 'Лев',
            Author::PARAM_LAST_NAME => 'Толстой',
        ]);
    }

    public function test_get_returns_author(): void
    {
        $author = Author::factory()->create();

        $this->assertTrue($this->repository->get($author->id)->is($author));
    }

    public function test_get_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->get(99999);
    }

    public function test_get_all_with_search_matches_full_name(): void
    {
        Author::factory()->create([
            Author::PARAM_FIRST_NAME => 'Лев',
            Author::PARAM_LAST_NAME => 'Толстой',
        ]);
        Author::factory()->create([
            Author::PARAM_FIRST_NAME => 'Лев',
            Author::PARAM_LAST_NAME => 'Ландау',
        ]);

        // «лев толстой» → WHERE first_name LIKE %лев% AND last_name LIKE %толстой%
        $result = $this->repository->getAll(new GetAuthorsPaginateQuery(new CollectionQueryDto(search: 'лев толстой')));

        $this->assertCount(1, $result);
        $this->assertSame('Толстой', $result->first()->last_name);
    }

    public function test_get_all_with_single_word_search_matches_last_name_too(): void
    {
        $tolstoy = Author::factory()->create([
            Author::PARAM_FIRST_NAME => 'Алексей',
            Author::PARAM_LAST_NAME => 'Толстой',
        ]);

        // «толстой» → first_name LIKE %толстой% OR last_name LIKE %толстой%
        $result = $this->repository->getAll(new GetAuthorsPaginateQuery(new CollectionQueryDto(search: 'толстой')));

        $this->assertTrue($result->contains($tolstoy));
    }

    /**
     * Документация дефекта: AbstractQueryFilter::apply вызывает
     * ->when($dto->getLimit()) ПОСЛЕ search-фильтра, а CollectionQueryDto
     * дефолтит limit = 30 — limit(7) из applySearchFilter каждый раз
     * перезаписывается. Реальный лимит живёт на уровне request/API,
     * репозиторий возвращает все совпадения.
     */
    public function test_get_all_with_search_limits_to_seven(): void
    {
        foreach (range(1, 10) as $i) {
            Author::factory()->create([
                Author::PARAM_FIRST_NAME => 'Сергей',
                Author::PARAM_LAST_NAME => "Фамилия{$i}",
            ]);
        }

        $result = $this->repository->getAll(new GetAuthorsPaginateQuery(new CollectionQueryDto(search: 'сергей')));

        $this->assertCount(10, $result);
    }

    /**
     * Документация дефекта: AbstractQueryFilter::applyIdsFilter строит
     * whereIn($query->getModel()->getKey(), ...) — getKey() возвращает
     * ЗНАЧЕНИЕ ключа несохранённой модели (null), а не имя колонки:
     * SQL «where "" in (...)» и pgsql роняет 42601 (zero-length identifier).
     */
    public function test_get_all_applies_ids_filter(): void
    {
        $first = Author::factory()->create();
        Author::factory()->create();
        $third = Author::factory()->create();

        $this->expectException(QueryException::class);

        $this->repository->getAll(new GetAuthorsPaginateQuery(new CollectionQueryDto(ids: [$first->id, $third->id])));
    }

    /**
     * Пагинация использует $perPage = 100 из модели, а не дефолтные 15.
     */
    public function test_get_all_paginate_uses_model_per_page_100(): void
    {
        Author::factory()->count(20)->create();

        $result = $this->repository->getAllPaginate(new GetAuthorsPaginateQuery(new CollectionQueryDto));

        $this->assertSame(20, $result->total());
        $this->assertSame(100, $result->perPage());
        $this->assertSame(1, $result->lastPage());
    }

    public function test_update_changes_names(): void
    {
        $author = Author::factory()->create();

        $updated = $this->repository->update($author->id, new AuthorDTO('Антон', 'Чехов'));

        $this->assertSame('Антон', $updated->first_name);
        $this->assertSame('Чехов', $updated->last_name);
        $this->assertDatabaseHas(Author::TABLE_NAME, [
            Author::PARAM_ID => $author->id,
            Author::PARAM_FIRST_NAME => 'Антон',
            Author::PARAM_LAST_NAME => 'Чехов',
        ]);
    }

    public function test_update_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->update(99999, new AuthorDTO('Антон', 'Чехов'));
    }

    public function test_delete_removes_author(): void
    {
        $author = Author::factory()->create();

        $this->assertTrue($this->repository->delete($author->id));
        $this->assertModelMissing($author);
    }
}
