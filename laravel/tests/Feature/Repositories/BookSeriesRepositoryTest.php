<?php

namespace Tests\Feature\Repositories;

use App\CQRS\Queries\BookSeries\GetBookSeriesQuery;
use App\DTOs\BookSeriesDTO;
use App\DTOs\CollectionQueryDto;
use App\Models\BookSeries;
use App\Repositories\BookSeriesRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Репозиторий BookSeries — глобальный справочник без скоупов;
 * фильтры CollectionQueryDto игнорируются.
 */
class BookSeriesRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private BookSeriesRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(BookSeriesRepository::class);
    }

    public function test_create_persists_series(): void
    {
        $series = $this->repository->create(new BookSeriesDTO('Мировая классика'));

        $this->assertDatabaseHas(BookSeries::TABLE_NAME, [
            BookSeries::PARAM_ID => $series->id,
            BookSeries::PARAM_NAME => 'Мировая классика',
        ]);
    }

    public function test_get_returns_series(): void
    {
        $series = BookSeries::factory()->create();

        $this->assertTrue($this->repository->get($series->id)->is($series));
    }

    public function test_get_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->get(99999);
    }

    /**
     * Документация текущего поведения: getAll() = BookSeries::all(),
     * фильтры из CollectionQueryDto (limit/search/ids) игнорируются.
     */
    public function test_get_all_ignores_query_filters(): void
    {
        BookSeries::factory()->count(3)->create();

        $result = $this->repository->getAll(new GetBookSeriesQuery(new CollectionQueryDto(limit: 1)));

        $this->assertCount(3, $result);
    }

    public function test_get_all_paginate_returns_default_paginator(): void
    {
        BookSeries::factory()->count(20)->create();

        $result = $this->repository->getAllPaginate(new GetBookSeriesQuery(new CollectionQueryDto));

        $this->assertSame(20, $result->total());
        $this->assertSame(15, $result->perPage());
        $this->assertSame(2, $result->lastPage());
    }

    public function test_update_changes_name(): void
    {
        $series = BookSeries::factory()->create();

        $updated = $this->repository->update($series->id, new BookSeriesDTO('Зарубежная классика'));

        $this->assertSame('Зарубежная классика', $updated->name);
        $this->assertDatabaseHas(BookSeries::TABLE_NAME, [
            BookSeries::PARAM_ID => $series->id,
            BookSeries::PARAM_NAME => 'Зарубежная классика',
        ]);
    }

    public function test_update_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->update(99999, new BookSeriesDTO('Зарубежная классика'));
    }

    public function test_delete_removes_series(): void
    {
        $series = BookSeries::factory()->create();

        $this->assertTrue($this->repository->delete($series->id));
        $this->assertModelMissing($series);
    }
}
