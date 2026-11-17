<?php

namespace Tests\Unit\CQRS;

use App\CQRS\Bus;
use App\CQRS\Commands\Cities\CreateCityCommand;
use App\CQRS\Commands\Cities\DestroyCityCommand;
use App\CQRS\Commands\Cities\UpdateCityCommand;
use App\CQRS\Handlers\Cities\CreateCityHandler;
use App\CQRS\Handlers\Cities\DestroyCityHandler;
use App\CQRS\Handlers\Cities\GetCitiesHandler;
use App\CQRS\Handlers\Cities\GetCityByIdHandler;
use App\CQRS\Handlers\Cities\UpdateCityHandler;
use App\CQRS\Queries\Cities\GetCitiesQuery;
use App\CQRS\Queries\Cities\GetCityByIdQuery;
use App\DTOs\CityDTO;
use App\DTOs\CollectionQueryDtoInterface;
use App\DTOs\DtoInterface;
use Illuminate\Contracts\Container\Container;
use PHPUnit\Framework\TestCase;

/**
 * Домен-специфичная проверка конвенции «Cities/{Command,Query} →
 * Handlers/Cities/{...}Handler». Общие guard'ы шины — в BusTest.
 *
 * Особенность: GetCityByIdHandler существует и конвенция его находит,
 * но CityProvider не привязывает RepositoryInterface к этому хендлеру
 * (см. feature-тест: BindingResolutionException), а сам хендлер по
 * сигнатуре возвращает PaperType — двойной дефект, путь show через
 * API недостижим (роут show исключён).
 */
class CitiesBusMappingTest extends TestCase
{
    public function test_create_command_resolves_to_create_handler(): void
    {
        $this->assertResolved(
            new CreateCityCommand($this->createStub(DtoInterface::class)),
            CreateCityHandler::class,
        );
    }

    public function test_update_command_resolves_to_update_handler(): void
    {
        $this->assertResolved(
            new UpdateCityCommand(1, $this->createStub(DtoInterface::class)),
            UpdateCityHandler::class,
        );
    }

    public function test_destroy_command_resolves_to_destroy_handler(): void
    {
        $this->assertResolved(
            new DestroyCityCommand(1),
            DestroyCityHandler::class,
        );
    }

    public function test_get_cities_query_resolves_to_get_cities_handler(): void
    {
        $this->assertResolved(
            new GetCitiesQuery($this->createStub(CollectionQueryDtoInterface::class)),
            GetCitiesHandler::class,
        );
    }

    public function test_get_by_id_query_resolves_to_get_by_id_handler(): void
    {
        $this->assertResolved(
            new GetCityByIdQuery(1),
            GetCityByIdHandler::class,
        );
    }

    public function test_command_with_dto_passes_same_instance_to_handler(): void
    {
        $dto = new CityDTO('KZN', 'Казань');
        $command = new CreateCityCommand($dto);

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
