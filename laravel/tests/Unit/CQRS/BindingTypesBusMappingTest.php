<?php

namespace Tests\Unit\CQRS;

use App\CQRS\Bus;
use App\CQRS\Commands\BindingTypes\CreateBindingTypeCommand;
use App\CQRS\Commands\BindingTypes\DestroyBindingTypeCommand;
use App\CQRS\Commands\BindingTypes\UpdateBindingTypeCommand;
use App\CQRS\Handlers\BindingTypes\CreateBindingTypeHandler;
use App\CQRS\Handlers\BindingTypes\DestroyBindingTypeHandler;
use App\CQRS\Handlers\BindingTypes\GetBindingTypesHandler;
use App\CQRS\Handlers\BindingTypes\UpdateBindingTypeHandler;
use App\CQRS\Queries\BindingTypes\GetBindingTypeByIdQuery;
use App\CQRS\Queries\BindingTypes\GetBindingTypesQuery;
use App\DTOs\BindingTyperDTO;
use App\DTOs\CollectionQueryDtoInterface;
use App\DTOs\DtoInterface;
use App\Exceptions\HandlerNotFoundException;
use Illuminate\Contracts\Container\Container;
use PHPUnit\Framework\TestCase;

/**
 * Домен-специфичная проверка конвенции «BindingTypes/{Command,Query} →
 * Handlers/BindingTypes/{...}Handler». Общие guard'ы шины — в BusTest.
 *
 * Отличие домена: есть GET /binding_types/{id} запрос
 * GetBindingTypeByIdQuery, но класс GetBindingTypeByIdHandler не
 * существует — resolve падает с HandlerNotFoundException ещё до контейнера.
 * Путь show через API недостижим (роут show исключён).
 */
class BindingTypesBusMappingTest extends TestCase
{
    public function test_create_command_resolves_to_create_handler(): void
    {
        $this->assertResolved(
            new CreateBindingTypeCommand($this->createStub(DtoInterface::class)),
            CreateBindingTypeHandler::class,
        );
    }

    public function test_update_command_resolves_to_update_handler(): void
    {
        $this->assertResolved(
            new UpdateBindingTypeCommand(1, $this->createStub(DtoInterface::class)),
            UpdateBindingTypeHandler::class,
        );
    }

    public function test_destroy_command_resolves_to_destroy_handler(): void
    {
        $this->assertResolved(
            new DestroyBindingTypeCommand(1),
            DestroyBindingTypeHandler::class,
        );
    }

    public function test_get_types_query_resolves_to_get_types_handler(): void
    {
        $this->assertResolved(
            new GetBindingTypesQuery($this->createStub(CollectionQueryDtoInterface::class)),
            GetBindingTypesHandler::class,
        );
    }

    public function test_get_by_id_query_fails_with_handler_not_found(): void
    {
        $this->expectException(HandlerNotFoundException::class);
        $this->expectExceptionMessage(
            'Handler App\CQRS\Handlers\BindingTypes\GetBindingTypeByIdHandler '
            .'for '.GetBindingTypeByIdQuery::class.' was not found',
        );

        (new Bus($this->createMock(Container::class)))
            ->handle(new GetBindingTypeByIdQuery(1));
    }

    public function test_command_with_dto_passes_same_instance_to_handler(): void
    {
        $dto = new BindingTyperDTO('Твёрдый переплёт');
        $command = new CreateBindingTypeCommand($dto);

        $stubHandler = new class
        {
            public ?object $received = null;

            public function handle(object $message): object
            {
                $this->received = $message;

                return $message;
            }
        };

        $container = $this->createMock(Container::class);
        $container->method('make')->willReturn($stubHandler);

        $this->assertSame($command, (new Bus($container))->handle($command));
        $this->assertSame($dto, $stubHandler->received->dto);
    }

    private function assertResolved(object $message, string $expectedHandler): void
    {
        $handler = new class
        {
            public function handle(object $message): string
            {
                return 'handled';
            }
        };

        $container = $this->createMock(Container::class);
        $container->expects($this->once())
            ->method('make')
            ->with($expectedHandler)
            ->willReturn($handler);

        $this->assertSame('handled', (new Bus($container))->handle($message));
    }
}
