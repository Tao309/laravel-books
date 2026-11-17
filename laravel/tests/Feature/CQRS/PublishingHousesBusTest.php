<?php

namespace Tests\Feature\CQRS;

use App\CQRS\Bus;
use App\CQRS\Commands\PublishingHouses\CreatePublishingHouseCommand;
use App\CQRS\Commands\PublishingHouses\DestroyPublishingHouseCommand;
use App\CQRS\Commands\PublishingHouses\UpdatePublishingHouseCommand;
use App\CQRS\Queries\PublishingHouses\GetPublishingHouseByIdQuery;
use App\CQRS\Queries\PublishingHouses\GetPublishingHousesQuery;
use App\DTOs\CollectionQueryDto;
use App\DTOs\PublishingHouseDTO;
use App\Models\PublishingHouse;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Сквозной путь команды/запроса через реальный контейнер и репозиторий.
 *
 * Отличие от PublishingBrandsBusTest: в PublishingHouseProvider нет контекстной
 * привязки RepositoryInterface → DestroyPublishingHouseHandler, поэтому путь
 * удаления падает с BindingResolutionException — тест это документирует.
 * После добавления binding ожидание нужно перевернуть на успешное удаление.
 */
class PublishingHousesBusTest extends TestCase
{
    use RefreshDatabase;

    private Bus $bus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bus = $this->app->make(Bus::class);
    }

    public function test_create_command_persists_house(): void
    {
        $house = $this->bus->handle(new CreatePublishingHouseCommand(
            new PublishingHouseDTO('Полярная звезда'),
        ));

        $this->assertInstanceOf(PublishingHouse::class, $house);
        $this->assertDatabaseHas(PublishingHouse::TABLE_NAME, [
            PublishingHouse::PARAM_ID => $house->id,
            PublishingHouse::PARAM_NAME => 'Полярная звезда',
        ]);
    }

    public function test_update_command_changes_name(): void
    {
        $house = PublishingHouse::factory()->create();

        $updated = $this->bus->handle(new UpdatePublishingHouseCommand(
            $house->id,
            new PublishingHouseDTO('Серебряный ключ'),
        ));

        $this->assertSame('Серебряный ключ', $updated->name);
    }

    public function test_destroy_command_fails_without_repository_binding(): void
    {
        $house = PublishingHouse::factory()->create();

        // Контекстная привязка RepositoryInterface → DestroyPublishingHouseHandler
        // отсутствует в PublishingHouseProvider (в отличие от PublishingBrandProvider)
        $this->expectException(BindingResolutionException::class);
        $this->expectExceptionMessage(
            'Target [App\Repositories\Interfaces\RepositoryInterface] is not instantiable while building [App\CQRS\Handlers\PublishingHouses\DestroyPublishingHouseHandler].',
        );

        $this->bus->handle(new DestroyPublishingHouseCommand($house->id));
    }

    public function test_get_houses_query_returns_all(): void
    {
        PublishingHouse::factory()->count(3)->create();

        $result = $this->bus->handle(new GetPublishingHousesQuery(new CollectionQueryDto));

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(3, $result);
    }

    public function test_get_house_by_id_query_returns_model(): void
    {
        $house = PublishingHouse::factory()->create();

        $this->assertTrue(
            $this->bus->handle(new GetPublishingHouseByIdQuery($house->id))->is($house),
        );
    }

    public function test_get_house_by_id_query_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->bus->handle(new GetPublishingHouseByIdQuery(99999));
    }
}
