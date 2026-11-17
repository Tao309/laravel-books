<?php

namespace Tests\Feature\Services;

use App\DTOs\DtoInterface;
use App\Services\ShopService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * ShopService write-методы: create/updateById/deleteById.
 *
 * Документация дефекта: ShopProvider биндит RepositoryInterface только
 * для read-хендлеров (GetShops/GetShopById), поэтому CreateShopHandler /
 * UpdateShopHandler / DestroyShopHandler не разрешаются контейнером —
 * все три метода падают BindingResolutionException, у сервисов они
 * недостижимы (в API write-роуты магазинов тоже не зарегистрированы).
 * Если ShopProvider начнёт биндить и write-хендлеры — тесты нужно
 * перевернуть на реальные сценарии.
 */
class ShopServiceTest extends TestCase
{
    use RefreshDatabase;

    private ShopService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(ShopService::class);
    }

    public function test_create_throws_without_repository_binding(): void
    {
        $this->expectException(BindingResolutionException::class);
        $this->expectExceptionMessage(
            'Target [App\Repositories\Interfaces\RepositoryInterface] is not instantiable while building [App\CQRS\Handlers\Shops\CreateShopHandler].',
        );

        $this->service->create($this->createStub(DtoInterface::class));
    }

    public function test_update_by_id_throws_without_repository_binding(): void
    {
        $this->expectException(BindingResolutionException::class);

        $this->service->updateById(1, $this->createStub(DtoInterface::class));
    }

    public function test_delete_by_id_throws_without_repository_binding(): void
    {
        $this->expectException(BindingResolutionException::class);

        $this->service->deleteById(1);
    }
}
