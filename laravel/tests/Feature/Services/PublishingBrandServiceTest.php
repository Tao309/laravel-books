<?php

namespace Tests\Feature\Services;

use App\Services\PublishingBrandService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Документация дефекта: PublishingBrandService::deleteById →
 * DestroyPublishingBrandCommand, хендлер разрешается, но
 * PublishingBrandProvider не привязывает к нему RepositoryInterface —
 * метод падает с BindingResolutionException, удаление недостижимо
 * (см. PublishingBrandsBusTest). Если привязку добавят — тест нужно
 * перевернуть на реальное удаление.
 */
class PublishingBrandServiceTest extends TestCase
{
    use RefreshDatabase;

    private PublishingBrandService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(PublishingBrandService::class);
    }

    public function test_delete_by_id_fails_without_repository_binding(): void
    {
        $this->expectException(BindingResolutionException::class);
        $this->expectExceptionMessage(
            'Target [App\Repositories\Interfaces\RepositoryInterface] is not instantiable while building [App\CQRS\Handlers\PublishingBrands\DestroyPublishingBrandHandler].',
        );

        $this->service->deleteById(1);
    }
}
