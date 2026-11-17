<?php

namespace Tests\Unit\CQRS;

use App\CQRS\Bus;
use App\CQRS\Commands\Books\CreateBookCommand;
use App\CQRS\Commands\Books\DestroyBookCommand;
use App\CQRS\Commands\Books\UpdateBookCommand;
use App\CQRS\Handlers\Books\CreateBookHandler;
use App\CQRS\Handlers\Books\DestroyBookHandler;
use App\CQRS\Handlers\Books\GetBookByIdHandler;
use App\CQRS\Handlers\Books\GetBooksHandler;
use App\CQRS\Handlers\Books\GetBooksPaginateHandler;
use App\CQRS\Handlers\Books\UpdateBookHandler;
use App\CQRS\Queries\Books\GetBookByIdQuery;
use App\CQRS\Queries\Books\GetBooksPaginateQuery;
use App\CQRS\Queries\Books\GetBooksQuery;
use App\DTOs\BookDTO;
use App\DTOs\CollectionQueryDtoInterface;
use App\DTOs\DtoInterface;
use App\Models\BindingType;
use App\Models\Book;
use Illuminate\Contracts\Container\Container;
use PHPUnit\Framework\TestCase;

/**
 * Домен-специфичная проверка конвенции «Books/{Command,Query} →
 * Handlers/Books/{...}Handler». Общие guard'ы шины — в BusTest.
 *
 * Отличие от Authors: кроме пагинации есть невырожденный
 * GetBooksQuery → GetBooksHandler (обычный список).
 */
class BooksBusMappingTest extends TestCase
{
    public function test_create_command_resolves_to_create_handler(): void
    {
        $this->assertResolved(
            new CreateBookCommand($this->createStub(DtoInterface::class)),
            CreateBookHandler::class,
        );
    }

    public function test_update_command_resolves_to_update_handler(): void
    {
        $this->assertResolved(
            new UpdateBookCommand(1, $this->createStub(DtoInterface::class)),
            UpdateBookHandler::class,
        );
    }

    public function test_destroy_command_resolves_to_destroy_handler(): void
    {
        $this->assertResolved(
            new DestroyBookCommand(1),
            DestroyBookHandler::class,
        );
    }

    public function test_get_books_query_resolves_to_get_books_handler(): void
    {
        $this->assertResolved(
            new GetBooksQuery($this->createStub(CollectionQueryDtoInterface::class)),
            GetBooksHandler::class,
        );
    }

    public function test_get_paginate_query_resolves_to_paginate_handler(): void
    {
        $this->assertResolved(
            new GetBooksPaginateQuery($this->createStub(CollectionQueryDtoInterface::class)),
            GetBooksPaginateHandler::class,
        );
    }

    public function test_get_by_id_query_resolves_to_get_by_id_handler(): void
    {
        $this->assertResolved(
            new GetBookByIdQuery(1),
            GetBookByIdHandler::class,
        );
    }

    public function test_command_with_dto_passes_same_instance_to_handler(): void
    {
        $dto = BookDTO::fromArray([
            Book::PARAM_TITLE => 'Книга через шину',
            Book::PARAM_PUBLISH_YEAR => '2020',
            Book::PARAM_BINDING_TYPE => [BindingType::PARAM_ID => 3],
            Book::PARAM_AUTHORS => [],
        ]);
        $command = new CreateBookCommand($dto);

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
