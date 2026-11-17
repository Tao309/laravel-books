<?php

namespace Tests\Feature\Repositories;

use App\CQRS\Queries\PublishingBrands\GetPublishingBrandsQuery;
use App\DTOs\CollectionQueryDto;
use App\DTOs\PublishingBrandDTO;
use App\Models\PublishingBrand;
use App\Repositories\PublishingBrandRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Репозиторий PublishingBrand не знает ни о магазине, ни о пользователе —
 * это глобальный справочник: скоупов и whenLoaded-связей здесь нет.
 */
class PublishingBrandRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private PublishingBrandRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(PublishingBrandRepository::class);
    }

    public function test_create_persists_brand(): void
    {
        $brand = $this->repository->create(new PublishingBrandDTO('Красная звезда'));

        $this->assertDatabaseHas(PublishingBrand::TABLE_NAME, [
            PublishingBrand::PARAM_ID => $brand->id,
            PublishingBrand::PARAM_NAME => 'Красная звезда',
        ]);
    }

    public function test_get_returns_brand(): void
    {
        $brand = PublishingBrand::factory()->create();

        $found = $this->repository->get($brand->id);

        $this->assertTrue($found->is($brand));
    }

    public function test_get_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->get(99999);
    }

    /**
     * Документация текущего поведения: getAll() = PublishingBrand::all(),
     * фильтры из CollectionQueryDto (limit/search/ids) игнорируются.
     */
    public function test_get_all_ignores_query_filters(): void
    {
        PublishingBrand::factory()->count(3)->create();

        $result = $this->repository->getAll(new GetPublishingBrandsQuery(new CollectionQueryDto(limit: 1)));

        $this->assertCount(3, $result);
    }

    public function test_get_all_paginate_returns_paginator(): void
    {
        PublishingBrand::factory()->count(20)->create();

        $result = $this->repository->getAllPaginate(new GetPublishingBrandsQuery(new CollectionQueryDto));

        $this->assertSame(20, $result->total());
        $this->assertSame(15, $result->perPage());
        $this->assertSame(2, $result->lastPage());
    }

    public function test_update_changes_name(): void
    {
        $brand = PublishingBrand::factory()->create();

        $updated = $this->repository->update($brand->id, new PublishingBrandDTO('Серебряная луна'));

        $this->assertSame('Серебряная луна', $updated->name);
        $this->assertDatabaseHas(PublishingBrand::TABLE_NAME, [
            PublishingBrand::PARAM_ID => $brand->id,
            PublishingBrand::PARAM_NAME => 'Серебряная луна',
        ]);
    }

    public function test_update_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->update(99999, new PublishingBrandDTO('Несуществующий'));
    }

    public function test_delete_removes_brand(): void
    {
        $brand = PublishingBrand::factory()->create();

        $this->assertTrue($this->repository->delete($brand->id));
        $this->assertModelMissing($brand);
    }
}
