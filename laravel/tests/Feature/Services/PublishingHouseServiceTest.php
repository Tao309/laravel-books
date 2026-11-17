<?php

namespace Tests\Feature\Services;

use App\Services\PublishingHouseService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Документация дефекта: PublishingHouseService::deleteById →
 * DestroyPublishingHouseCommand, хендлер разрешается, но
 * PublishingHouseProvider не привязывает к нему RepositoryInterface —
 * метод падает с BindingResolutionException, удаление недостижимо
 * (см. PublishingHousesBusTest). Если привязку добавят — тест нужно
 * перевернуть на реальное удаление.
 */
class PublishingHouseServiceTest extends TestCase
{
    use RefreshDatabase;

    private PublishingHouseService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(PublishingHouseService::class);
    }

    public function test_delete_by_id_fails_without_repository_binding(): void
    {
        $this->expectException(BindingResolutionException::class);
        $this->expectExceptionMessage(
            'Target [App\Repositories\Interfaces\RepositoryInterface] is not instantiable while building [App\CQRS\Handlers\PublishingHouses\DestroyPublishingHouseHandler].',
        );

        $this->service->deleteById(1);
    }
}
