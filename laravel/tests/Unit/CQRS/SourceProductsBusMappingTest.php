<?php

namespace Tests\Unit\CQRS;

use App\CQRS\Bus;
use App\CQRS\Commands\SourceProducts\CreateSourceProductCommand;
use App\CQRS\Commands\SourceProducts\DestroySourceProductCommand;
use App\CQRS\Commands\SourceProducts\UpdateSourceProductCommand;
use App\CQRS\Handlers\SourceProducts\CreateSourceProductHandler;
use App\CQRS\Handlers\SourceProducts\DestroySourceProductHandler;
use App\CQRS\Handlers\SourceProducts\GetSourceProductByIdSHandler;
use App\CQRS\Handlers\SourceProducts\GetSourceProductsHandler;
use App\CQRS\Handlers\SourceProducts\GetSourceProductsPaginateHandler;
use App\CQRS\Handlers\SourceProducts\UpdateSourceProductHandler;
use App\CQRS\Queries\SourceProducts\GetSourceProductByIdSQuery;
use App\CQRS\Queries\SourceProducts\GetSourceProductsPaginateQuery;
use App\CQRS\Queries\SourceProducts\GetSourceProductsQuery;
use App\DTOs\CollectionQueryDtoInterface;
use App\DTOs\DtoInterface;
use App\DTOs\SourceProductDTO;
use Illuminate\Contracts\Container\Container;
use PHPUnit\Framework\TestCase;

/**
 * Домен-специфичная проверка конвенции «SourceProducts/{Command,Query} →
 * Handlers/SourceProducts/{...}Handler». Общие guard'ы шины — в BusTest.
 *
 * Особенность: запрос getById назван GetSourceProductByIdSQuery (с лишней
 * S), поэтому конвенция находит хендлер GetSourceProductByIdSHandler —
 * имена совпали. Пагинат-запрос маппится, но хендлер не привязан
 * в SourceProductProvider (см. feature-тест).
 */
class SourceProductsBusMappingTest extends TestCase
{
    public function test_create_command_resolves_to_create_handler(): void
    {
        $this->assertResolved(
            new CreateSourceProductCommand($this->createStub(DtoInterface::class)),
            CreateSourceProductHandler::class,
        );
    }

    public function test_update_command_resolves_to_update_handler(): void
    {
        $this->assertResolved(
            new UpdateSourceProductCommand(1, $this->createStub(DtoInterface::class)),
            UpdateSourceProductHandler::class,
        );
    }

    public function test_destroy_command_resolves_to_destroy_handler(): void
    {
        $this->assertResolved(
            new DestroySourceProductCommand(1),
            DestroySourceProductHandler::class,
        );
    }

    public function test_get_products_query_resolves_to_get_products_handler(): void
    {
        $this->assertResolved(
            new GetSourceProductsQuery($this->createStub(CollectionQueryDtoInterface::class)),
            GetSourceProductsHandler::class,
        );
    }

    public function test_get_by_id_query_with_odd_suffix_resolves_to_matching_handler(): void
    {
        // GetSourceProductByIdSQuery → GetSourceProductByIdSHandler
        // (нестандартное имя, но конвенция «S»+Handler совпадает)
        $this->assertResolved(
            new GetSourceProductByIdSQuery(1),
            GetSourceProductByIdSHandler::class,
        );
    }

    public function test_get_paginate_query_resolves_to_paginate_handler(): void
    {
        // маппинг валиден; контейнер не привязывает RepositoryInterface
        // к этому хендлеру — проверяется в SourceProductsBusTest
        $this->assertResolved(
            new GetSourceProductsPaginateQuery(
                $this->createStub(CollectionQueryDtoInterface::class),
            ),
            GetSourceProductsPaginateHandler::class,
        );
    }

    public function test_command_with_dto_passes_same_instance_to_handler(): void
    {
        $dto = new SourceProductDTO('Кроссовки лето', ['id' => 7], []);
        $command = new CreateSourceProductCommand($dto);

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
