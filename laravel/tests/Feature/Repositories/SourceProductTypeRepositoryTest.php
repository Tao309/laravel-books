<?php

namespace Tests\Feature\Repositories;

use App\CQRS\Queries\SourceProductTypes\GetSourceProductTypesQuery;
use App\DTOs\CollectionQueryDto;
use App\DTOs\SourceProductTypeDTO;
use App\Models\SourceProductType;
use App\Repositories\SourceProductTypeRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Репозиторий SourceProductType — глобальный справочник без скоупов;
 * фильтры CollectionQueryDto игнорируются.
 */
class SourceProductTypeRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private SourceProductTypeRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(SourceProductTypeRepository::class);
    }

    public function test_create_persists_type(): void
    {
        $type = $this->repository->create(new SourceProductTypeDTO('shoes', 'Обувь'));

        $this->assertDatabaseHas(SourceProductType::TABLE_NAME, [
            SourceProductType::PARAM_ID => $type->id,
            SourceProductType::PARAM_CODE => 'shoes',
            SourceProductType::PARAM_NAME => 'Обувь',
        ]);
    }

    public function test_get_returns_type(): void
    {
        $type = SourceProductType::factory()->create();

        $this->assertTrue($this->repository->get($type->id)->is($type));
    }

    public function test_get_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->get(99999);
    }

    /**
     * Документация текущего поведения: getAll() = SourceProductType::all(),
     * фильтры из CollectionQueryDto (limit/search/ids) игнорируются.
     */
    public function test_get_all_ignores_query_filters(): void
    {
        SourceProductType::factory()
            ->count(3)
            ->sequence(fn ($sequence) => [
                SourceProductType::PARAM_CODE => "repo-type-{$sequence->index}",
                SourceProductType::PARAM_NAME => "Тип репозитория {$sequence->index}",
            ])
            ->create();

        $result = $this->repository->getAll(new GetSourceProductTypesQuery(new CollectionQueryDto(limit: 1)));

        $this->assertCount(3, $result);
    }

    public function test_get_all_paginate_returns_default_paginator(): void
    {
        SourceProductType::factory()
            ->count(20)
            ->sequence(fn ($sequence) => [
                SourceProductType::PARAM_CODE => "repo-page-{$sequence->index}",
                SourceProductType::PARAM_NAME => "Страница {$sequence->index}",
            ])
            ->create();

        $result = $this->repository->getAllPaginate(new GetSourceProductTypesQuery(new CollectionQueryDto));

        $this->assertSame(20, $result->total());
        $this->assertSame(15, $result->perPage());
        $this->assertSame(2, $result->lastPage());
    }

    public function test_update_changes_code_and_name(): void
    {
        $type = SourceProductType::factory()->create();

        $updated = $this->repository->update($type->id, new SourceProductTypeDTO('shirts', 'Футболки'));

        $this->assertSame('shirts', $updated->code);
        $this->assertSame('Футболки', $updated->name);
        $this->assertDatabaseHas(SourceProductType::TABLE_NAME, [
            SourceProductType::PARAM_ID => $type->id,
            SourceProductType::PARAM_CODE => 'shirts',
            SourceProductType::PARAM_NAME => 'Футболки',
        ]);
    }

    public function test_update_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->update(99999, new SourceProductTypeDTO('shirts', 'Футболки'));
    }

    public function test_delete_removes_type(): void
    {
        $type = SourceProductType::factory()->create();

        $this->assertTrue($this->repository->delete($type->id));
        $this->assertModelMissing($type);
    }
}
