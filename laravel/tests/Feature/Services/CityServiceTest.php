<?php

namespace Tests\Feature\Services;

use App\Models\City;
use App\Services\CityService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * CityService::deleteById — Bus → DestroyCityCommand →
 * CityRepository::delete (findOrFail + delete).
 */
class CityServiceTest extends TestCase
{
    use RefreshDatabase;

    private CityService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(CityService::class);
    }

    public function test_delete_by_id_removes_city(): void
    {
        $city = City::factory()->create([
            City::PARAM_CODE => 'MSK',
            City::PARAM_TITLE => 'Москва',
        ]);

        $this->assertTrue($this->service->deleteById($city->id));

        $this->assertDatabaseMissing(City::TABLE_NAME, [
            City::PARAM_ID => $city->id,
        ]);
    }

    /**
     * Документация дефекта: CityService::getById → GetCityByIdQuery,
     * хендлер существует, но CityProvider не привязывает к нему
     * RepositoryInterface (show-роут городов закомментирован) — метод
     * падает с BindingResolutionException для любого id (см.
     * CitiesBusTest::test_get_by_id_query_fails_without_repository_binding).
     * Если привязку добавят — тест нужно перевернуть на реальные
     * сценарии (return model / ModelNotFoundException).
     */
    public function test_get_by_id_fails_without_repository_binding(): void
    {
        $city = City::factory()->create();

        $this->expectException(BindingResolutionException::class);
        $this->expectExceptionMessage(
            'Target [App\Repositories\Interfaces\RepositoryInterface] is not instantiable while building [App\CQRS\Handlers\Cities\GetCityByIdHandler].',
        );

        $this->service->getById($city->id);
    }

    public function test_get_by_id_missing_fails_without_repository_binding(): void
    {
        $this->expectException(BindingResolutionException::class);

        $this->service->getById(99999);
    }
}
