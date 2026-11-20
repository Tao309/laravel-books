<?php

namespace Tests\Unit\CQRS;

use App\CQRS\Bus;
use App\CQRS\Commands\SourceProductTypes\CreateSourceProductTypeCommand;
use App\CQRS\Commands\SourceProductTypes\DestroySourceProductTypeCommand;
use App\CQRS\Commands\SourceProductTypes\UpdateSourceProductTypeCommand;
use App\CQRS\Handlers\SourceProductTypes\CreateSourceProductTypeHandler;
use App\CQRS\Handlers\SourceProductTypes\DestroySourceProductTypeHandler;
use App\CQRS\Handlers\SourceProductTypes\GetSourceProductByIdTypeHandler;
use App\CQRS\Handlers\SourceProductTypes\GetSourceProductTypesHandler;
use App\CQRS\Handlers\SourceProductTypes\UpdateSourceProductTypeHandler;
use App\CQRS\Queries\SourceProductTypes\GetSourceProductTypeByIdQuery;
use App\CQRS\Queries\SourceProductTypes\GetSourceProductTypesQuery;
use App\DTOs\CollectionQueryDtoInterface;
use App\DTOs\DtoInterface;
use App\DTOs\SourceProductTypeDTO;
use App\Exceptions\HandlerNotFoundException;
use Illuminate\Contracts\Container\Container;
use PHPUnit\Framework\TestCase;

/**
 * Домен-специфичная проверка конвенции «SourceProductTypes/{Command,Query} →
 * Handlers/SourceProductTypes/{...}Handler». Общие guard'ы шины — в BusTest.
 *
 * Дефект именования: хендлер назван GetSourceProductByIdTypeHandler, а
 * запрос — GetSourceProductTypeByIdQuery; конвенция ищет
 * GetSourceProductTypeByIdHandler → class_exists false →
 * HandlerNotFoundException. Путь show через API падает (см. API-тест).
 */
class SourceProductTypesBusMappingTest extends TestCase
{
    public function test_create_command_resolves_to_create_handler(): void
    {
        $this->assertResolved(
            new CreateSourceProductTypeCommand($this->createStub(DtoInterface::class)),
            CreateSourceProductTypeHandler::class,
        );
    }

    public function test_update_command_resolves_to_update_handler(): void
    {
        $this->assertResolved(
            new UpdateSourceProductTypeCommand(1, $this->createStub(DtoInterface::class)),
            UpdateSourceProductTypeHandler::class,
        );
    }

    public function test_destroy_command_resolves_to_destroy_handler(): void
    {
        $this->assertResolved(
            new DestroySourceProductTypeCommand(1),
            DestroySourceProductTypeHandler::class,
        );
    }

    public function test_get_types_query_resolves_to_get_types_handler(): void
    {
        $this->assertResolved(
            new GetSourceProductTypesQuery($this->createStub(CollectionQueryDtoInterface::class)),
            GetSourceProductTypesHandler::class,
        );
    }

    public function test_get_by_id_query_fails_with_handler_not_found(): void
    {
        $this->expectException(HandlerNotFoundException::class);
        $this->expectExceptionMessage(
            'Handler App\CQRS\Handlers\SourceProductTypes\GetSourceProductTypeByIdHandler '
            . 'for ' . GetSourceProductTypeByIdQuery::class . ' was not found',
        );

        (new Bus($this->createMock(Container::class)))
            ->handle(new GetSourceProductTypeByIdQuery(1));
    }

    public function test_command_with_dto_passes_same_instance_to_handler(): void
    {
        $dto = new SourceProductTypeDTO('shoes', 'Обувь');
        $command = new CreateSourceProductTypeCommand($dto);

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
