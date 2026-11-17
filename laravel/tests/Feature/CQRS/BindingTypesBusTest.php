<?php

namespace Tests\Feature\CQRS;

use App\CQRS\Bus;
use App\CQRS\Commands\BindingTypes\CreateBindingTypeCommand;
use App\CQRS\Commands\BindingTypes\DestroyBindingTypeCommand;
use App\CQRS\Commands\BindingTypes\UpdateBindingTypeCommand;
use App\CQRS\Queries\BindingTypes\GetBindingTypeByIdQuery;
use App\CQRS\Queries\BindingTypes\GetBindingTypesQuery;
use App\DTOs\BindingTyperDTO;
use App\DTOs\CollectionQueryDto;
use App\Exceptions\HandlerNotFoundException;
use App\Models\BindingType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Сквозной путь команды/запроса через реальный контейнер и репозиторий.
 *
 * У BindingType есть живой destroy (роут + привязка), но нет show:
 * запрос GetBindingTypeByIdQuery падает с HandlerNotFoundException,
 * т.к. класса хендлера не существует (см. BindingTypesBusMappingTest).
 */
class BindingTypesBusTest extends TestCase
{
    use RefreshDatabase;

    private Bus $bus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bus = $this->app->make(Bus::class);
    }

    public function test_create_command_persists_binding_type(): void
    {
        $bindingType = $this->bus->handle(new CreateBindingTypeCommand(
            new BindingTyperDTO('Твёрдый переплёт'),
        ));

        $this->assertInstanceOf(BindingType::class, $bindingType);
        $this->assertDatabaseHas(BindingType::TABLE_NAME, [
            BindingType::PARAM_ID => $bindingType->id,
            BindingType::PARAM_LABEL => 'Твёрдый переплёт',
        ]);
    }

    public function test_update_command_changes_label(): void
    {
        $bindingType = BindingType::factory()->create();

        $updated = $this->bus->handle(new UpdateBindingTypeCommand(
            $bindingType->id,
            new BindingTyperDTO('Мягкая обложка'),
        ));

        $this->assertSame('Мягкая обложка', $updated->label);
    }

    public function test_destroy_command_deletes_binding_type(): void
    {
        $bindingType = BindingType::factory()->create();

        $this->assertTrue($this->bus->handle(new DestroyBindingTypeCommand($bindingType->id)));
        $this->assertModelMissing($bindingType);
    }

    public function test_destroy_command_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->bus->handle(new DestroyBindingTypeCommand(99999));
    }

    public function test_get_types_query_returns_all(): void
    {
        BindingType::factory()->count(3)->create();

        $result = $this->bus->handle(new GetBindingTypesQuery(new CollectionQueryDto));

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(3, $result);
    }

    public function test_get_by_id_query_fails_with_handler_not_found(): void
    {
        $this->expectException(HandlerNotFoundException::class);

        $this->bus->handle(new GetBindingTypeByIdQuery(1));
    }
}
