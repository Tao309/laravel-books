<?php

namespace Tests\Feature\Services;

use App\Exceptions\HandlerNotFoundException;
use App\Services\BookSeriesService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Документация дефекта: BookSeriesService::deleteById →
 * DestroyBookSeriesCommand, но хендлера
 * App\CQRS\Handlers\BookSeries\DestroyBookSeriesHandler не существует
 * (в папке BookSeries лежит класс DestroyBookHandler — он ищется
 * конвенцией в домене Books и отсюда недостижим) — Bus кидает
 * HandlerNotFoundException (см. BookSeriesBusTest). Если хендлер
 * добавят/переименуют — тест нужно перевернуть на реальное удаление.
 */
class BookSeriesServiceTest extends TestCase
{
    use RefreshDatabase;

    private BookSeriesService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(BookSeriesService::class);
    }

    public function test_delete_by_id_fails_with_handler_not_found(): void
    {
        $this->expectException(HandlerNotFoundException::class);
        $this->expectExceptionMessage(
            'Handler App\CQRS\Handlers\BookSeries\DestroyBookSeriesHandler '
            . 'for App\CQRS\Commands\BookSeries\DestroyBookSeriesCommand was not found',
        );

        $this->service->deleteById(1);
    }
}
