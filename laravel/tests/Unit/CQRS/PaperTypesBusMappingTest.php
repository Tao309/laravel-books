<?php

namespace Tests\Unit\CQRS;

use App\CQRS\Bus;
use App\CQRS\Commands\PaperTypes\CreatePaperTypeCommand;
use App\CQRS\Commands\PaperTypes\DestroyPaperTypeCommand;
use App\CQRS\Commands\PaperTypes\UpdatePaperTypeCommand;
use App\CQRS\Handlers\PaperTypes\CreatePaperTypeHandler;
use App\CQRS\Handlers\PaperTypes\DestroyPaperTypeHandler;
use App\CQRS\Handlers\PaperTypes\GetPaperTypeByIdHandler;
use App\CQRS\Handlers\PaperTypes\GetPaperTypesHandler;
use App\CQRS\Handlers\PaperTypes\GetPaperTypesPaginateHandler;
use App\CQRS\Handlers\PaperTypes\UpdatePaperTypeHandler;
use App\CQRS\Queries\PaperTypes\GetPaperTypeByIdQuery;
use App\CQRS\Queries\PaperTypes\GetPaperTypesPaginateQuery;
use App\CQRS\Queries\PaperTypes\GetPaperTypesQuery;
use App\DTOs\CollectionQueryDtoInterface;
use App\DTOs\DtoInterface;
use App\DTOs\PaperTypeDTO;
use Illuminate\Contracts\Container\Container;
use PHPUnit\Framework\TestCase;

/**
 * Домен-специфичная проверка конвенции «PaperTypes/{Command,Query} →
 * Handlers/PaperTypes/{...}Handler». Общие guard'ы шины — в BusTest.
 *
 * У PaperType полный набор сообщений, включая оба варианта коллекции:
 * GetPaperTypesQuery и GetPaperTypesPaginateQuery.
 */
class PaperTypesBusMappingTest extends TestCase
{
    public function test_create_command_resolves_to_create_handler(): void
    {
        $this->assertResolved(
            new CreatePaperTypeCommand($this->createStub(DtoInterface::class)),
            CreatePaperTypeHandler::class,
        );
    }

    public function test_update_command_resolves_to_update_handler(): void
    {
        $this->assertResolved(
            new UpdatePaperTypeCommand(1, $this->createStub(DtoInterface::class)),
            UpdatePaperTypeHandler::class,
        );
    }

    public function test_destroy_command_resolves_to_destroy_handler(): void
    {
        $this->assertResolved(
            new DestroyPaperTypeCommand(1),
            DestroyPaperTypeHandler::class,
        );
    }

    public function test_get_types_query_resolves_to_get_types_handler(): void
    {
        $this->assertResolved(
            new GetPaperTypesQuery($this->createStub(CollectionQueryDtoInterface::class)),
            GetPaperTypesHandler::class,
        );
    }

    public function test_get_types_paginate_query_resolves_to_paginate_handler(): void
    {
        $this->assertResolved(
            new GetPaperTypesPaginateQuery($this->createStub(CollectionQueryDtoInterface::class)),
            GetPaperTypesPaginateHandler::class,
        );
    }

    public function test_get_by_id_query_resolves_to_get_by_id_handler(): void
    {
        $this->assertResolved(
            new GetPaperTypeByIdQuery(1),
            GetPaperTypeByIdHandler::class,
        );
    }

    public function test_command_with_dto_passes_same_instance_to_handler(): void
    {
        $dto = new PaperTypeDTO('Мелованная', null);
        $command = new CreatePaperTypeCommand($dto);

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
