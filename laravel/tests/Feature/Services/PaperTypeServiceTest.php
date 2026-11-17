<?php

namespace Tests\Feature\Services;

use App\DTOs\CollectionQueryDto;
use App\Services\PaperTypeService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Документация дефекта: PaperTypeService::getListPaginate →
 * GetPaperTypesPaginateQuery, но PaperTypeProvider не привязывает
 * RepositoryInterface к GetPaperTypesPaginateHandler — метод падает
 * с BindingResolutionException, пагинация недостижима через сервис
 * (та же картина в SourceProducts/SourceProductTypes, см. их BusTest).
 * Если привязку добавят — тест нужно перевернуть на реальный пагинат.
 */
class PaperTypeServiceTest extends TestCase
{
    use RefreshDatabase;

    private PaperTypeService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(PaperTypeService::class);
    }

    public function test_get_list_paginate_fails_without_repository_binding(): void
    {
        $this->expectException(BindingResolutionException::class);
        $this->expectExceptionMessage(
            'Target [App\Repositories\Interfaces\RepositoryInterface] is not instantiable while building [App\CQRS\Handlers\PaperTypes\GetPaperTypesPaginateHandler].',
        );

        $this->service->getListPaginate(new CollectionQueryDto);
    }
}
