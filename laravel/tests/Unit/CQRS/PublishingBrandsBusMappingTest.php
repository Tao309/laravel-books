<?php

namespace Tests\Unit\CQRS;

use App\CQRS\Bus;
use App\CQRS\Commands\PublishingBrands\CreatePublishingBrandCommand;
use App\CQRS\Commands\PublishingBrands\DestroyPublishingBrandCommand;
use App\CQRS\Commands\PublishingBrands\UpdatePublishingBrandCommand;
use App\CQRS\Handlers\PublishingBrands\CreatePublishingBrandHandler;
use App\CQRS\Handlers\PublishingBrands\DestroyPublishingBrandHandler;
use App\CQRS\Handlers\PublishingBrands\GetPublishingBrandByIdHandler;
use App\CQRS\Handlers\PublishingBrands\GetPublishingBrandsHandler;
use App\CQRS\Handlers\PublishingBrands\UpdatePublishingBrandHandler;
use App\CQRS\Queries\PublishingBrands\GetPublishingBrandByIdQuery;
use App\CQRS\Queries\PublishingBrands\GetPublishingBrandsQuery;
use App\DTOs\CollectionQueryDtoInterface;
use App\DTOs\DtoInterface;
use App\DTOs\PublishingBrandDTO;
use Illuminate\Contracts\Container\Container;
use PHPUnit\Framework\TestCase;

/**
 * Домен-специфичная проверка конвенции «PublishingBrands/{Command,Query} →
 * Handlers/PublishingBrands/{...}Handler». Общие guard'ы шины — в BusTest.
 */
class PublishingBrandsBusMappingTest extends TestCase
{
    public function test_create_command_resolves_to_create_handler(): void
    {
        $this->assertResolved(
            new CreatePublishingBrandCommand($this->createStub(DtoInterface::class)),
            CreatePublishingBrandHandler::class,
        );
    }

    public function test_update_command_resolves_to_update_handler(): void
    {
        $this->assertResolved(
            new UpdatePublishingBrandCommand(1, $this->createStub(DtoInterface::class)),
            UpdatePublishingBrandHandler::class,
        );
    }

    public function test_destroy_command_resolves_to_destroy_handler(): void
    {
        $this->assertResolved(
            new DestroyPublishingBrandCommand(1),
            DestroyPublishingBrandHandler::class,
        );
    }

    public function test_get_brands_query_resolves_to_get_brands_handler(): void
    {
        $this->assertResolved(
            new GetPublishingBrandsQuery($this->createStub(CollectionQueryDtoInterface::class)),
            GetPublishingBrandsHandler::class,
        );
    }

    public function test_get_brand_by_id_query_resolves_to_get_by_id_handler(): void
    {
        $this->assertResolved(
            new GetPublishingBrandByIdQuery(1),
            GetPublishingBrandByIdHandler::class,
        );
    }

    public function test_command_with_dto_passes_same_instance_to_handler(): void
    {
        $dto = new PublishingBrandDTO('Красная звезда');
        $command = new CreatePublishingBrandCommand($dto);

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
