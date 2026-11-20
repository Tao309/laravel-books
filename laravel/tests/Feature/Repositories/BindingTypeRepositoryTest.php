<?php

namespace Tests\Feature\Repositories;

use App\CQRS\Queries\BindingTypes\GetBindingTypesQuery;
use App\DTOs\BindingTyperDTO;
use App\DTOs\CollectionQueryDto;
use App\Models\BindingType;
use App\Repositories\BindingTypeRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Репозиторий BindingType — глобальный справочник без скоупов;
 * фильтры CollectionQueryDto игнорируются.
 */
class BindingTypeRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private BindingTypeRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(BindingTypeRepository::class);
    }

    public function test_create_persists_binding_type(): void
    {
        $bindingType = $this->repository->create(new BindingTyperDTO('Твёрдый переплёт'));

        $this->assertDatabaseHas(BindingType::TABLE_NAME, [
            BindingType::PARAM_ID => $bindingType->id,
            BindingType::PARAM_LABEL => 'Твёрдый переплёт',
        ]);
    }

    public function test_get_returns_binding_type(): void
    {
        $bindingType = BindingType::factory()->create();

        $this->assertTrue($this->repository->get($bindingType->id)->is($bindingType));
    }

    public function test_get_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->get(99999);
    }

    /**
     * Документация текущего поведения: getAll() = BindingType::all(),
     * фильтры из CollectionQueryDto (limit/search/ids) игнорируются.
     */
    public function test_get_all_ignores_query_filters(): void
    {
        BindingType::factory()->count(3)->create();

        $result = $this->repository->getAll(new GetBindingTypesQuery(new CollectionQueryDto(limit: 1)));

        $this->assertCount(3, $result);
    }

    public function test_get_all_paginate_returns_default_paginator(): void
    {
        // fake()->word() коллизирует по binding_types_label_unique при 20
        // строках — задаём детерминированные уникальные label
        BindingType::factory()
            ->count(20)
            ->sequence(fn ($sequence) => [
                BindingType::PARAM_LABEL => 'Тип обложки номер ' . $sequence->index,
            ])
            ->create();

        $result = $this->repository->getAllPaginate(new GetBindingTypesQuery(new CollectionQueryDto));

        $this->assertSame(20, $result->total());
        $this->assertSame(15, $result->perPage());
        $this->assertSame(2, $result->lastPage());
    }

    public function test_update_changes_label(): void
    {
        $bindingType = BindingType::factory()->create();

        $updated = $this->repository->update($bindingType->id, new BindingTyperDTO('Мягкая обложка'));

        $this->assertSame('Мягкая обложка', $updated->label);
        $this->assertDatabaseHas(BindingType::TABLE_NAME, [
            BindingType::PARAM_ID => $bindingType->id,
            BindingType::PARAM_LABEL => 'Мягкая обложка',
        ]);
    }

    public function test_update_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->update(99999, new BindingTyperDTO('Мягкая обложка'));
    }

    public function test_delete_removes_binding_type(): void
    {
        $bindingType = BindingType::factory()->create();

        $this->assertTrue($this->repository->delete($bindingType->id));
        $this->assertModelMissing($bindingType);
    }
}
