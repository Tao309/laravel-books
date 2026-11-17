<?php

namespace Tests\Feature\CQRS;

use App\CQRS\Bus;
use App\CQRS\Commands\SourceProductTypes\CreateSourceProductTypeCommand;
use App\CQRS\Commands\SourceProductTypes\DestroySourceProductTypeCommand;
use App\CQRS\Commands\SourceProductTypes\UpdateSourceProductTypeCommand;
use App\CQRS\Queries\SourceProductTypes\GetSourceProductTypeByIdQuery;
use App\CQRS\Queries\SourceProductTypes\GetSourceProductTypesPaginateQuery;
use App\CQRS\Queries\SourceProductTypes\GetSourceProductTypesQuery;
use App\DTOs\CollectionQueryDto;
use App\DTOs\SourceProductTypeDTO;
use App\Exceptions\HandlerNotFoundException;
use App\Models\SourceProductType;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Сквозной путь команды/запроса через реальный контейнер и репозиторий.
 *
 * Два дефекта домена:
 *  - getByIdQuery → HandlerNotFoundException (хендлер назван
 *    GetSourceProductByIdTypeHandler, конвенция ищет GetSourceProductTypeByIdHandler);
 *  - paginate-запрос маппится в существующий GetSourceProductTypesPaginateHandler,
 *    но SourceProductTypeProvider не привязывает к нему RepositoryInterface →
 *    BindingResolutionException (роуты используют только невырожденный index).
 */
class SourceProductTypesBusTest extends TestCase
{
    use RefreshDatabase;

    private Bus $bus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bus = $this->app->make(Bus::class);
    }

    public function test_create_command_persists_type(): void
    {
        $type = $this->bus->handle(new CreateSourceProductTypeCommand(
            new SourceProductTypeDTO('shoes', 'Обувь'),
        ));

        $this->assertInstanceOf(SourceProductType::class, $type);
        $this->assertDatabaseHas(SourceProductType::TABLE_NAME, [
            SourceProductType::PARAM_ID => $type->id,
            SourceProductType::PARAM_CODE => 'shoes',
            SourceProductType::PARAM_NAME => 'Обувь',
        ]);
    }

    public function test_update_command_changes_code_and_name(): void
    {
        $type = SourceProductType::factory()->create();

        $updated = $this->bus->handle(new UpdateSourceProductTypeCommand(
            $type->id,
            new SourceProductTypeDTO('shirts', 'Футболки'),
        ));

        $this->assertSame('shirts', $updated->code);
        $this->assertSame('Футболки', $updated->name);
    }

    public function test_destroy_command_deletes_type(): void
    {
        $type = SourceProductType::factory()->create();

        $this->assertTrue($this->bus->handle(new DestroySourceProductTypeCommand($type->id)));
        $this->assertModelMissing($type);
    }

    public function test_destroy_command_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->bus->handle(new DestroySourceProductTypeCommand(99999));
    }

    public function test_get_types_query_returns_all(): void
    {
        SourceProductType::factory()
            ->count(3)
            ->sequence(fn ($sequence) => [
                SourceProductType::PARAM_CODE => "bus-type-{$sequence->index}",
                SourceProductType::PARAM_NAME => "Тип шины {$sequence->index}",
            ])
            ->create();

        $result = $this->bus->handle(new GetSourceProductTypesQuery(new CollectionQueryDto));

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(3, $result);
    }

    public function test_get_by_id_query_fails_with_handler_not_found(): void
    {
        $type = SourceProductType::factory()->create();

        $this->expectException(HandlerNotFoundException::class);

        $this->bus->handle(new GetSourceProductTypeByIdQuery($type->id));
    }

    public function test_get_types_paginate_query_fails_without_repository_binding(): void
    {
        SourceProductType::factory()
            ->count(3)
            ->sequence(fn ($sequence) => [
                SourceProductType::PARAM_CODE => "bus-page-{$sequence->index}",
                SourceProductType::PARAM_NAME => "Тип пагинации {$sequence->index}",
            ])
            ->create();

        $this->expectException(BindingResolutionException::class);
        $this->expectExceptionMessage(
            'Target [App\Repositories\Interfaces\RepositoryInterface] is not instantiable while building [App\CQRS\Handlers\SourceProductTypes\GetSourceProductTypesPaginateHandler].',
        );

        $this->bus->handle(new GetSourceProductTypesPaginateQuery(new CollectionQueryDto));
    }
}
