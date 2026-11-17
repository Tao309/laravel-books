<?php

namespace Tests\Feature\Repositories;

use App\CQRS\Queries\PublishingHouses\GetPublishingHousesQuery;
use App\DTOs\CollectionQueryDto;
use App\DTOs\PublishingHouseDTO;
use App\Models\PublishingHouse;
use App\Repositories\PublishingHouseRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Репозиторий PublishingHouse не знает ни о магазине, ни о пользователе —
 * это глобальный справочник: скоупов и whenLoaded-связей здесь нет.
 */
class PublishingHouseRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private PublishingHouseRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(PublishingHouseRepository::class);
    }

    public function test_create_persists_house(): void
    {
        $house = $this->repository->create(new PublishingHouseDTO('Полярная звезда'));

        $this->assertDatabaseHas(PublishingHouse::TABLE_NAME, [
            PublishingHouse::PARAM_ID => $house->id,
            PublishingHouse::PARAM_NAME => 'Полярная звезда',
        ]);
    }

    public function test_get_returns_house(): void
    {
        $house = PublishingHouse::factory()->create();

        $this->assertTrue($this->repository->get($house->id)->is($house));
    }

    public function test_get_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->get(99999);
    }

    /**
     * Документация текущего поведения: getAll() = PublishingHouse::all(),
     * фильтры из CollectionQueryDto (limit/search/ids) игнорируются.
     */
    public function test_get_all_ignores_query_filters(): void
    {
        PublishingHouse::factory()->count(3)->create();

        $result = $this->repository->getAll(new GetPublishingHousesQuery(new CollectionQueryDto(limit: 1)));

        $this->assertCount(3, $result);
    }

    public function test_get_all_paginate_returns_paginator(): void
    {
        PublishingHouse::factory()->count(20)->create();

        $result = $this->repository->getAllPaginate(new GetPublishingHousesQuery(new CollectionQueryDto));

        $this->assertSame(20, $result->total());
        $this->assertSame(15, $result->perPage());
        $this->assertSame(2, $result->lastPage());
    }

    public function test_update_changes_name(): void
    {
        $house = PublishingHouse::factory()->create();

        $updated = $this->repository->update($house->id, new PublishingHouseDTO('Серебряный ключ'));

        $this->assertSame('Серебряный ключ', $updated->name);
        $this->assertDatabaseHas(PublishingHouse::TABLE_NAME, [
            PublishingHouse::PARAM_ID => $house->id,
            PublishingHouse::PARAM_NAME => 'Серебряный ключ',
        ]);
    }

    public function test_update_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->update(99999, new PublishingHouseDTO('Несуществующая'));
    }

    public function test_delete_removes_house(): void
    {
        $house = PublishingHouse::factory()->create();

        $this->assertTrue($this->repository->delete($house->id));
        $this->assertModelMissing($house);
    }
}
