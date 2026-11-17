<?php

namespace Tests\Feature\Repositories;

use App\CQRS\Queries\Cities\GetCitiesQuery;
use App\DTOs\CityDTO;
use App\DTOs\CollectionQueryDto;
use App\Models\City;
use App\Repositories\CityRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Репозиторий City — глобальный справочник без скоупов;
 * фильтры CollectionQueryDto игнорируются.
 */
class CityRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private CityRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(CityRepository::class);
    }

    public function test_create_persists_city(): void
    {
        $city = $this->repository->create(new CityDTO('KZN', 'Казань'));

        $this->assertDatabaseHas(City::TABLE_NAME, [
            City::PARAM_ID => $city->id,
            City::PARAM_CODE => 'KZN',
            City::PARAM_TITLE => 'Казань',
        ]);
    }

    public function test_get_returns_city(): void
    {
        $city = City::factory()->create();

        $this->assertTrue($this->repository->get($city->id)->is($city));
    }

    public function test_get_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->get(99999);
    }

    /**
     * Документация текущего поведения: getAll() = City::all(),
     * фильтры из CollectionQueryDto (limit/search/ids) игнорируются.
     */
    public function test_get_all_ignores_query_filters(): void
    {
        City::factory()->count(3)->create();

        $result = $this->repository->getAll(new GetCitiesQuery(new CollectionQueryDto(limit: 1)));

        $this->assertCount(3, $result);
    }

    public function test_get_all_paginate_returns_default_paginator(): void
    {
        City::factory()->count(20)->create();

        $result = $this->repository->getAllPaginate(new GetCitiesQuery(new CollectionQueryDto));

        $this->assertSame(20, $result->total());
        $this->assertSame(15, $result->perPage());
        $this->assertSame(2, $result->lastPage());
    }

    public function test_update_changes_code_and_title(): void
    {
        $city = City::factory()->create();

        $updated = $this->repository->update($city->id, new CityDTO('MSK', 'Москва'));

        $this->assertSame('MSK', $updated->code);
        $this->assertSame('Москва', $updated->title);
        $this->assertDatabaseHas(City::TABLE_NAME, [
            City::PARAM_ID => $city->id,
            City::PARAM_CODE => 'MSK',
            City::PARAM_TITLE => 'Москва',
        ]);
    }

    public function test_update_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->update(99999, new CityDTO('MSK', 'Москва'));
    }

    public function test_delete_removes_city(): void
    {
        $city = City::factory()->create();

        $this->assertTrue($this->repository->delete($city->id));
        $this->assertModelMissing($city);
    }
}
