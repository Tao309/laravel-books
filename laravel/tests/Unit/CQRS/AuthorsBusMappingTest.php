<?php

namespace Tests\Unit\CQRS;

use App\CQRS\Bus;
use App\CQRS\Commands\Authors\CreateAuthorCommand;
use App\CQRS\Commands\Authors\DestroyAuthorCommand;
use App\CQRS\Commands\Authors\UpdateAuthorCommand;
use App\CQRS\Handlers\Authors\CreateAuthorHandler;
use App\CQRS\Handlers\Authors\DestroyAuthorHandler;
use App\CQRS\Handlers\Authors\GetAuthorByIdHandler;
use App\CQRS\Handlers\Authors\GetAuthorsPaginateHandler;
use App\CQRS\Handlers\Authors\UpdateAuthorHandler;
use App\CQRS\Queries\Authors\GetAuthorByIdQuery;
use App\CQRS\Queries\Authors\GetAuthorsPaginateQuery;
use App\DTOs\AuthorDTO;
use App\DTOs\CollectionQueryDtoInterface;
use App\DTOs\DtoInterface;
use Illuminate\Contracts\Container\Container;
use PHPUnit\Framework\TestCase;

/**
 * Домен-специфичная проверка конвенции «Authors/{Command,Query} →
 * Handlers/Authors/{...}Handler». Общие guard'ы шины — в BusTest.
 *
 * Отличие от большинства доменов: у Author нет невырожденного
 * GetAuthorsQuery — только GetAuthorsPaginateQuery (пагинация по 100).
 */
class AuthorsBusMappingTest extends TestCase
{
    public function test_create_command_resolves_to_create_handler(): void
    {
        $this->assertResolved(
            new CreateAuthorCommand($this->createStub(DtoInterface::class)),
            CreateAuthorHandler::class,
        );
    }

    public function test_update_command_resolves_to_update_handler(): void
    {
        $this->assertResolved(
            new UpdateAuthorCommand(1, $this->createStub(DtoInterface::class)),
            UpdateAuthorHandler::class,
        );
    }

    public function test_destroy_command_resolves_to_destroy_handler(): void
    {
        $this->assertResolved(
            new DestroyAuthorCommand(1),
            DestroyAuthorHandler::class,
        );
    }

    public function test_get_paginate_query_resolves_to_paginate_handler(): void
    {
        $this->assertResolved(
            new GetAuthorsPaginateQuery($this->createStub(CollectionQueryDtoInterface::class)),
            GetAuthorsPaginateHandler::class,
        );
    }

    public function test_get_by_id_query_resolves_to_get_by_id_handler(): void
    {
        $this->assertResolved(
            new GetAuthorByIdQuery(1),
            GetAuthorByIdHandler::class,
        );
    }

    public function test_command_with_dto_passes_same_instance_to_handler(): void
    {
        $dto = new AuthorDTO('Лев', 'Толстой');
        $command = new CreateAuthorCommand($dto);

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
