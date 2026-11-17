<?php

namespace Tests\Feature\Services;

use App\DTOs\CollectionQueryDto;
use App\Services\SourceProductService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Документация дефекта: SourceProductService::getListPaginate →
 * GetSourceProductsPaginateQuery, но SourceProductProvider не привязывает
 * RepositoryInterface к GetSourceProductsPaginateHandler — метод падает
 * с BindingResolutionException, пагинация недостижима (см.
 * SourceProductsBusTest). Если привязку добавят — тест нужно перевернуть
 * на реальный пагинат.
 */
class SourceProductServiceTest extends TestCase
{
    use RefreshDatabase;

    private SourceProductService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(SourceProductService::class);
    }

    public function test_get_list_paginate_fails_without_repository_binding(): void
    {
        $this->expectException(BindingResolutionException::class);
        $this->expectExceptionMessage(
            'Target [App\Repositories\Interfaces\RepositoryInterface] is not instantiable while building [App\CQRS\Handlers\SourceProducts\GetSourceProductsPaginateHandler].',
        );

        $this->service->getListPaginate(new CollectionQueryDto);
    }
}
