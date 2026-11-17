<?php

namespace Tests\Feature\CQRS;

use App\CQRS\Bus;
use App\CQRS\Commands\Shops\CreateShopCommand;
use App\CQRS\Commands\Shops\DestroyShopCommand;
use App\CQRS\Commands\Shops\UpdateShopCommand;
use App\CQRS\Queries\Shops\GetShopByIdQuery;
use App\CQRS\Queries\Shops\GetShopsQuery;
use App\DTOs\CollectionQueryDto;
use App\DTOs\DtoInterface;
use App\Models\Shop;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Сквозной путь запроса через реальный контейнер и репозиторий.
 *
 * Shop — read-only домен: ShopProvider привязывает RepositoryInterface
 * только к GetShops/GetShopById. Командные хендлеры классы существуют,
 * но без привязки → BindingResolutionException; роуты store/update/
 * destroy не зарегистрированы. После добавления привязок тест нужно
 * перевернуть на успешные команды.
 */
class ShopsBusTest extends TestCase
{
    use RefreshDatabase;

    private Bus $bus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bus = $this->app->make(Bus::class);
    }

    public function test_get_shops_query_returns_all(): void
    {
        Shop::factory()->count(3)->create();

        $result = $this->bus->handle(new GetShopsQuery(new CollectionQueryDto));

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(3, $result);
    }

    public function test_get_shop_by_id_query_returns_model(): void
    {
        $shop = Shop::factory()->create();

        $this->assertTrue(
            $this->bus->handle(new GetShopByIdQuery($shop->id))->is($shop),
        );
    }

    public function test_get_shop_by_id_query_throws_for_missing_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->bus->handle(new GetShopByIdQuery(99999));
    }

    public function test_create_command_fails_without_repository_binding(): void
    {
        // Контекстная привязка RepositoryInterface → CreateShopHandler
        // отсутствует в ShopProvider (роут store тоже не зарегистрирован)
        $this->expectException(BindingResolutionException::class);
        $this->expectExceptionMessage(
            'Target [App\Repositories\Interfaces\RepositoryInterface] is not instantiable while building [App\CQRS\Handlers\Shops\CreateShopHandler].',
        );

        $this->bus->handle(new CreateShopCommand(
            $this->createStub(DtoInterface::class),
        ));
    }

    public function test_update_command_fails_without_repository_binding(): void
    {
        $this->expectException(BindingResolutionException::class);

        $this->bus->handle(new UpdateShopCommand(1, $this->createStub(DtoInterface::class)));
    }

    public function test_destroy_command_fails_without_repository_binding(): void
    {
        $this->expectException(BindingResolutionException::class);

        $this->bus->handle(new DestroyShopCommand(1));
    }
}
