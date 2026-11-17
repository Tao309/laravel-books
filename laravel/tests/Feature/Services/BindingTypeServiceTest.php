<?php

namespace Tests\Feature\Services;

use App\Exceptions\HandlerNotFoundException;
use App\Services\BindingTypeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Документация дефекта: BindingTypeService::getById →
 * GetBindingTypeByIdQuery, но хендлера
 * App\CQRS\Handlers\BindingTypes\GetBindingTypeByIdHandler не существует
 * (в домене только Create/Destroy/GetBindingTypes/Update) — Bus кидает
 * HandlerNotFoundException для ЛЮБОГО id, валидного и нет (см.
 * BindingTypesBusTest). Если хендлер добавят — тесты нужно перевернуть
 * на реальные сценарии (return model / ModelNotFoundException).
 */
class BindingTypeServiceTest extends TestCase
{
    use RefreshDatabase;

    private BindingTypeService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(BindingTypeService::class);
    }

    public function test_get_by_id_fails_with_handler_not_found(): void
    {
        $this->expectException(HandlerNotFoundException::class);

        $this->service->getById(1);
    }

    public function test_get_by_id_missing_fails_with_handler_not_found(): void
    {
        $this->expectException(HandlerNotFoundException::class);

        $this->service->getById(99999);
    }
}
