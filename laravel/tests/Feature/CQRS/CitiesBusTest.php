<?php

namespace Tests\Feature\CQRS;

use App\CQRS\Bus;
use App\CQRS\Commands\Cities\CreateCityCommand;
use App\CQRS\Commands\Cities\DestroyCityCommand;
use App\CQRS\Commands\Cities\UpdateCityCommand;
use App\CQRS\Queries\Cities\GetCitiesQuery;
use App\CQRS\Queries\Cities\GetCityByIdQuery;
use App\DTOs\CityDTO;
use App\DTOs\CollectionQueryDto;
use App\Models\City;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Сквозной путь команды/запроса через реальный контейнер и репозиторий.
 *
 * У City нет show-роута, а запрос GetCityByIdQuery сломан вдвойне:
 * CityProvider не привязывает RepositoryInterface к GetCityByIdHandler →
 * BindingResolutionException; сам хендлер ещё и возвращает PaperType.
 * После исправления привязки тест нужно дополнить проверкой типа.
 */
class CitiesBusTest extends TestCase
{
    use RefreshDatabase;

    private Bus $bus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bus = $this->app->make(Bus::class);
    }

    public function test_create_command_persists_city(): void
    {
        $city = $this->bus->handle(new CreateCityCommand(
            new CityDTO('KZN', 'Казань'),
        ));

        $this->assertInstanceOf(City::class, $city);
        $this->assertDatabaseHas(City::TABLE_NAME, [
            City::PARAM_ID => $city->id,
            City::PARAM_CODE => 'KZN',
            City::PARAM_TITLE => 'Казань',
        ]);
    }

    public function test_update_command_changes_code_and_title(): void
    {
        $city = City::factory()->create();

        $updated = $this->bus->handle(new UpdateCityCommand(
            $city->id,
            new CityDTO('MSK', 'Москва'),
        ));

        $this->assertSame('MSK', $updated->code);
        $this->assertSame('Москва', $updated->title);
    }

    public function test_destroy_command_deletes_city(): void
    {
        $city = City::factory()->create();

        $this->assertTrue($this->bus->handle(new DestroyCityCommand($city->id)));
        $this->assertModelMissing($city);
    }

    public function test_destroy_command_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->bus->handle(new DestroyCityCommand(99999));
    }

    public function test_get_cities_query_returns_all(): void
    {
        City::factory()->count(3)->create();

        $result = $this->bus->handle(new GetCitiesQuery(new CollectionQueryDto));

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(3, $result);
    }

    public function test_get_by_id_query_fails_without_repository_binding(): void
    {
        $city = City::factory()->create();

        // Контекстная привязка RepositoryInterface → GetCityByIdHandler
        // отсутствует в CityProvider (роут show при этом тоже исключён)
        $this->expectException(BindingResolutionException::class);
        $this->expectExceptionMessage(
            'Target [App\Repositories\Interfaces\RepositoryInterface] is not instantiable while building [App\CQRS\Handlers\Cities\GetCityByIdHandler].',
        );

        $this->bus->handle(new GetCityByIdQuery($city->id));
    }
}
