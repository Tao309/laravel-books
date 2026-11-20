<?php

namespace Tests\Unit\CQRS;

use App\CQRS\Bus;
use App\CQRS\Commands\BookSeries\CreateBookSeriesCommand;
use App\CQRS\Commands\BookSeries\DestroyBookSeriesCommand;
use App\CQRS\Commands\BookSeries\UpdateBookSeriesCommand;
use App\CQRS\Handlers\BookSeries\CreateBookSeriesHandler;
use App\CQRS\Handlers\BookSeries\GetBookSeriesByIdHandler;
use App\CQRS\Handlers\BookSeries\GetBookSeriesHandler;
use App\CQRS\Handlers\BookSeries\UpdateBookSeriesHandler;
use App\CQRS\Queries\BookSeries\GetBookSeriesByIdQuery;
use App\CQRS\Queries\BookSeries\GetBookSeriesQuery;
use App\DTOs\BookSeriesDTO;
use App\DTOs\CollectionQueryDtoInterface;
use App\DTOs\DtoInterface;
use App\Exceptions\HandlerNotFoundException;
use Illuminate\Contracts\Container\Container;
use PHPUnit\Framework\TestCase;

/**
 * Домен-специфичная проверка конвенции «BookSeries/{Command,Query} →
 * Handlers/BookSeries/{...}Handler». Общие guard'ы шины — в BusTest.
 *
 * Отличие домена: DestroyBookSeriesCommand существует, но хендлера
 * DestroyBookSeriesHandler нет (в папке BookSeries лежит DestroyBookHandler
 * для команд домена Books) — resolve падает с HandlerNotFoundException.
 * Через API путь недостижим: destroy-роут исключён.
 */
class BookSeriesBusMappingTest extends TestCase
{
    public function test_create_command_resolves_to_create_handler(): void
    {
        $this->assertResolved(
            new CreateBookSeriesCommand($this->createStub(DtoInterface::class)),
            CreateBookSeriesHandler::class,
        );
    }

    public function test_update_command_resolves_to_update_handler(): void
    {
        $this->assertResolved(
            new UpdateBookSeriesCommand(1, $this->createStub(DtoInterface::class)),
            UpdateBookSeriesHandler::class,
        );
    }

    public function test_destroy_command_fails_with_handler_not_found(): void
    {
        $this->expectException(HandlerNotFoundException::class);
        $this->expectExceptionMessage(
            'Handler App\CQRS\Handlers\BookSeries\DestroyBookSeriesHandler '
            . 'for ' . DestroyBookSeriesCommand::class . ' was not found',
        );

        (new Bus($this->createMock(Container::class)))
            ->handle(new DestroyBookSeriesCommand(1));
    }

    public function test_get_series_query_resolves_to_get_series_handler(): void
    {
        $this->assertResolved(
            new GetBookSeriesQuery($this->createStub(CollectionQueryDtoInterface::class)),
            GetBookSeriesHandler::class,
        );
    }

    public function test_get_by_id_query_resolves_to_get_by_id_handler(): void
    {
        $this->assertResolved(
            new GetBookSeriesByIdQuery(1),
            GetBookSeriesByIdHandler::class,
        );
    }

    public function test_command_with_dto_passes_same_instance_to_handler(): void
    {
        $dto = new BookSeriesDTO('Мировая классика');
        $command = new CreateBookSeriesCommand($dto);

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
