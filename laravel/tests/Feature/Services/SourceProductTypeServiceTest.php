<?php

namespace Tests\Feature\Services;

use App\DTOs\CollectionQueryDto;
use App\Services\SourceProductTypeService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Документация дефекта: SourceProductTypeService::getListPaginate →
 * GetSourceProductTypesPaginateQuery, но SourceProductTypeProvider не
 * привязывает RepositoryInterface к GetSourceProductTypesPaginateHandler —
 * метод падает с BindingResolutionException, пагинация недостижима
 * (см. SourceProductTypesBusTest). Если привязку добавят — тест нужно
 * перевернуть на реальный пагинат.
 */
class SourceProductTypeServiceTest extends TestCase
{
    use RefreshDatabase;

    private SourceProductTypeService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(SourceProductTypeService::class);
    }

    public function test_get_list_paginate_fails_without_repository_binding(): void
    {
        $this->expectException(BindingResolutionException::class);
        $this->expectExceptionMessage(
            'Target [App\Repositories\Interfaces\RepositoryInterface] is not instantiable while building [App\CQRS\Handlers\SourceProductTypes\GetSourceProductTypesPaginateHandler].',
        );

        $this->service->getListPaginate(new CollectionQueryDto);
    }
}
