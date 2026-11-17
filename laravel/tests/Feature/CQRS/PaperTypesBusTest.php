<?php

namespace Tests\Feature\CQRS;

use App\CQRS\Bus;
use App\CQRS\Commands\PaperTypes\CreatePaperTypeCommand;
use App\CQRS\Commands\PaperTypes\DestroyPaperTypeCommand;
use App\CQRS\Commands\PaperTypes\UpdatePaperTypeCommand;
use App\CQRS\Queries\PaperTypes\GetPaperTypeByIdQuery;
use App\CQRS\Queries\PaperTypes\GetPaperTypesPaginateQuery;
use App\CQRS\Queries\PaperTypes\GetPaperTypesQuery;
use App\DTOs\CollectionQueryDto;
use App\DTOs\PaperTypeDTO;
use App\Models\PaperType;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Сквозной путь команды/запроса через реальный контейнер и репозиторий.
 * У PaperType полный набор привязок в PaperTypeProvider, включая Destroy —
 * путь удаления жив (destroy-роут зарегистрирован).
 */
class PaperTypesBusTest extends TestCase
{
    use RefreshDatabase;

    private Bus $bus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bus = $this->app->make(Bus::class);
    }

    public function test_create_command_persists_paper_type(): void
    {
        $paperType = $this->bus->handle(new CreatePaperTypeCommand(
            new PaperTypeDTO('Мелованная', 'С покрытием'),
        ));

        $this->assertInstanceOf(PaperType::class, $paperType);
        $this->assertDatabaseHas(PaperType::TABLE_NAME, [
            PaperType::PARAM_ID => $paperType->id,
            PaperType::PARAM_LABEL => 'Мелованная',
            PaperType::PARAM_DESCRIPTION => 'С покрытием',
        ]);
    }

    public function test_update_command_changes_label_and_description(): void
    {
        $paperType = PaperType::factory()->create();

        $updated = $this->bus->handle(new UpdatePaperTypeCommand(
            $paperType->id,
            new PaperTypeDTO('Офсетная', 'Гладкая'),
        ));

        $this->assertSame('Офсетная', $updated->label);
        $this->assertSame('Гладкая', $updated->description);
    }

    public function test_destroy_command_deletes_paper_type(): void
    {
        $paperType = PaperType::factory()->create();

        $this->assertTrue($this->bus->handle(new DestroyPaperTypeCommand($paperType->id)));
        $this->assertModelMissing($paperType);
    }

    public function test_destroy_command_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->bus->handle(new DestroyPaperTypeCommand(99999));
    }

    public function test_get_types_query_returns_all(): void
    {
        PaperType::factory()->count(3)->create();

        $result = $this->bus->handle(new GetPaperTypesQuery(new CollectionQueryDto));

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(3, $result);
    }

    public function test_get_types_by_id_query_returns_model(): void
    {
        $paperType = PaperType::factory()->create();

        $this->assertTrue(
            $this->bus->handle(new GetPaperTypeByIdQuery($paperType->id))->is($paperType),
        );
    }

    public function test_get_types_by_id_query_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->bus->handle(new GetPaperTypeByIdQuery(99999));
    }

    /**
     * Документация дефекта: PaperTypeProvider не привязывает
     * RepositoryInterface → GetPaperTypesPaginateHandler — paginate-запрос
     * падает с BindingResolutionException, хотя хендлер и запрос существуют
     * (PaperTypeService::getListPaginate недостижим; та же картина в
     * SourceProducts/SourceProductTypes). Если привязку добавят —
     * тест нужно перевернуть на реальный пагинат.
     */
    public function test_get_types_paginate_query_fails_without_repository_binding(): void
    {
        $this->expectException(BindingResolutionException::class);
        $this->expectExceptionMessage(
            'Target [App\Repositories\Interfaces\RepositoryInterface] is not instantiable while building [App\CQRS\Handlers\PaperTypes\GetPaperTypesPaginateHandler].',
        );

        $this->bus->handle(new GetPaperTypesPaginateQuery(new CollectionQueryDto));
    }
}
