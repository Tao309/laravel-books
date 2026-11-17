<?php

namespace Tests\Feature\Repositories;

use App\CQRS\Queries\PaperTypes\GetPaperTypesQuery;
use App\DTOs\CollectionQueryDto;
use App\DTOs\PaperTypeDTO;
use App\Models\PaperType;
use App\Repositories\PaperTypeRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Репозиторий PaperType — глобальный справочник без скоупов;
 * фильтры CollectionQueryDto игнорируются.
 */
class PaperTypeRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private PaperTypeRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(PaperTypeRepository::class);
    }

    public function test_create_persists_paper_type(): void
    {
        $paperType = $this->repository->create(new PaperTypeDTO('Мелованная', 'С покрытием'));

        $this->assertDatabaseHas(PaperType::TABLE_NAME, [
            PaperType::PARAM_ID => $paperType->id,
            PaperType::PARAM_LABEL => 'Мелованная',
            PaperType::PARAM_DESCRIPTION => 'С покрытием',
        ]);
    }

    public function test_create_persists_null_description(): void
    {
        $paperType = $this->repository->create(new PaperTypeDTO('Газетная', null));

        $this->assertNull($paperType->description);
        $this->assertDatabaseHas(PaperType::TABLE_NAME, [
            PaperType::PARAM_ID => $paperType->id,
            PaperType::PARAM_LABEL => 'Газетная',
            PaperType::PARAM_DESCRIPTION => null,
        ]);
    }

    public function test_get_returns_paper_type(): void
    {
        $paperType = PaperType::factory()->create();

        $this->assertTrue($this->repository->get($paperType->id)->is($paperType));
    }

    public function test_get_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->get(99999);
    }

    /**
     * Документация текущего поведения: getAll() = PaperType::all(),
     * фильтры из CollectionQueryDto (limit/search/ids) игнорируются.
     */
    public function test_get_all_ignores_query_filters(): void
    {
        PaperType::factory()->count(3)->create();

        $result = $this->repository->getAll(new GetPaperTypesQuery(new CollectionQueryDto(limit: 1)));

        $this->assertCount(3, $result);
    }

    public function test_get_all_paginate_returns_default_paginator(): void
    {
        PaperType::factory()->count(20)->create();

        $result = $this->repository->getAllPaginate(new GetPaperTypesQuery(new CollectionQueryDto));

        $this->assertSame(20, $result->total());
        $this->assertSame(15, $result->perPage());
        $this->assertSame(2, $result->lastPage());
    }

    public function test_update_changes_label_and_description(): void
    {
        $paperType = PaperType::factory()->create();

        $updated = $this->repository->update($paperType->id, new PaperTypeDTO('Офсетная', 'Гладкая'));

        $this->assertSame('Офсетная', $updated->label);
        $this->assertSame('Гладкая', $updated->description);
        $this->assertDatabaseHas(PaperType::TABLE_NAME, [
            PaperType::PARAM_ID => $paperType->id,
            PaperType::PARAM_LABEL => 'Офсетная',
            PaperType::PARAM_DESCRIPTION => 'Гладкая',
        ]);
    }

    public function test_update_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->update(99999, new PaperTypeDTO('Офсетная', null));
    }

    public function test_delete_removes_paper_type(): void
    {
        $paperType = PaperType::factory()->create();

        $this->assertTrue($this->repository->delete($paperType->id));
        $this->assertModelMissing($paperType);
    }
}
