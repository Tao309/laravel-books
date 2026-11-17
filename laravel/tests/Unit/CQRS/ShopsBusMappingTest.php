<?php

namespace Tests\Unit\CQRS;

use App\CQRS\Bus;
use App\CQRS\Commands\Shops\CreateShopCommand;
use App\CQRS\Commands\Shops\DestroyShopCommand;
use App\CQRS\Commands\Shops\UpdateShopCommand;
use App\CQRS\Handlers\Shops\CreateShopHandler;
use App\CQRS\Handlers\Shops\DestroyShopHandler;
use App\CQRS\Handlers\Shops\GetShopByIdHandler;
use App\CQRS\Handlers\Shops\GetShopsHandler;
use App\CQRS\Handlers\Shops\UpdateShopHandler;
use App\CQRS\Queries\Shops\GetShopByIdQuery;
use App\CQRS\Queries\Shops\GetShopsQuery;
use App\DTOs\CollectionQueryDtoInterface;
use App\DTOs\DtoInterface;
use Illuminate\Contracts\Container\Container;
use PHPUnit\Framework\TestCase;

/**
 * Домен-специфичная проверка конвенции «Shops/{Command,Query} →
 * Handlers/Shops/{...}Handler». Общие guard'ы шины — в BusTest.
 *
 * Конвенция находит все 5 хендлеров, но ShopProvider привязывает
 * RepositoryInterface только к GetShops/GetShopById — командные пути
 * падают с BindingResolutionException (см. ShopsBusTest).
 */
class ShopsBusMappingTest extends TestCase
{
    public function test_create_command_resolves_to_create_handler(): void
    {
        $this->assertResolved(
            new CreateShopCommand($this->createStub(DtoInterface::class)),
            CreateShopHandler::class,
        );
    }

    public function test_update_command_resolves_to_update_handler(): void
    {
        $this->assertResolved(
            new UpdateShopCommand(1, $this->createStub(DtoInterface::class)),
            UpdateShopHandler::class,
        );
    }

    public function test_destroy_command_resolves_to_destroy_handler(): void
    {
        $this->assertResolved(
            new DestroyShopCommand(1),
            DestroyShopHandler::class,
        );
    }

    public function test_get_shops_query_resolves_to_get_shops_handler(): void
    {
        $this->assertResolved(
            new GetShopsQuery($this->createStub(CollectionQueryDtoInterface::class)),
            GetShopsHandler::class,
        );
    }

    public function test_get_shop_by_id_query_resolves_to_get_by_id_handler(): void
    {
        $this->assertResolved(
            new GetShopByIdQuery(1),
            GetShopByIdHandler::class,
        );
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
