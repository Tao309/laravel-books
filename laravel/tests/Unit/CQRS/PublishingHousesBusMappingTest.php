<?php

namespace Tests\Unit\CQRS;

use App\CQRS\Bus;
use App\CQRS\Commands\PublishingHouses\CreatePublishingHouseCommand;
use App\CQRS\Commands\PublishingHouses\DestroyPublishingHouseCommand;
use App\CQRS\Commands\PublishingHouses\UpdatePublishingHouseCommand;
use App\CQRS\Handlers\PublishingHouses\CreatePublishingHouseHandler;
use App\CQRS\Handlers\PublishingHouses\DestroyPublishingHouseHandler;
use App\CQRS\Handlers\PublishingHouses\GetPublishingHouseByIdHandler;
use App\CQRS\Handlers\PublishingHouses\GetPublishingHousesHandler;
use App\CQRS\Handlers\PublishingHouses\UpdatePublishingHouseHandler;
use App\CQRS\Queries\PublishingHouses\GetPublishingHouseByIdQuery;
use App\CQRS\Queries\PublishingHouses\GetPublishingHousesQuery;
use App\DTOs\CollectionQueryDtoInterface;
use App\DTOs\DtoInterface;
use App\DTOs\PublishingHouseDTO;
use Illuminate\Contracts\Container\Container;
use PHPUnit\Framework\TestCase;

/**
 * Домен-специфичная проверка конвенции «PublishingHouses/{Command,Query} →
 * Handlers/PublishingHouses/{...}Handler». Общие guard'ы шины — в BusTest.
 */
class PublishingHousesBusMappingTest extends TestCase
{
    public function test_create_command_resolves_to_create_handler(): void
    {
        $this->assertResolved(
            new CreatePublishingHouseCommand($this->createStub(DtoInterface::class)),
            CreatePublishingHouseHandler::class,
        );
    }

    public function test_update_command_resolves_to_update_handler(): void
    {
        $this->assertResolved(
            new UpdatePublishingHouseCommand(1, $this->createStub(DtoInterface::class)),
            UpdatePublishingHouseHandler::class,
        );
    }

    public function test_destroy_command_resolves_to_destroy_handler(): void
    {
        $this->assertResolved(
            new DestroyPublishingHouseCommand(1),
            DestroyPublishingHouseHandler::class,
        );
    }

    public function test_get_houses_query_resolves_to_get_houses_handler(): void
    {
        $this->assertResolved(
            new GetPublishingHousesQuery($this->createStub(CollectionQueryDtoInterface::class)),
            GetPublishingHousesHandler::class,
        );
    }

    public function test_get_house_by_id_query_resolves_to_get_by_id_handler(): void
    {
        $this->assertResolved(
            new GetPublishingHouseByIdQuery(1),
            GetPublishingHouseByIdHandler::class,
        );
    }

    public function test_command_with_dto_passes_same_instance_to_handler(): void
    {
        $dto = new PublishingHouseDTO('Полярная звезда');
        $command = new CreatePublishingHouseCommand($dto);

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
