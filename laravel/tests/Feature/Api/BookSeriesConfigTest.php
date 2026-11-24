<?php

namespace Tests\Feature\Api;

use App\Models\BookSeries;
use Tests\Feature\Api\Concerns\ActsAsApiClient;
use Tests\TestCase;

/**
 * Сквозной тест кеша справочника через /api/config:
 * BookSeriesCacheProvider питает book_series,
 * а Observer сбрасывает кеш на изменениях модели.
 */
class BookSeriesConfigTest extends TestCase
{
    use ActsAsApiClient;

    public function test_config_contains_cached_series(): void
    {
        $series = BookSeries::factory()->create();

        $this->apiGet('/api/config')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.book_series.0.id', $series->id)
            ->assertJsonPath('data.book_series.0.name', $series->name);
    }

    public function test_store_invalidates_config_cache(): void
    {
        // первый прогрев кеша без серий
        $this->apiGet('/api/config')->assertOk();

        $this->apiPost('/api/book_series', ['name' => 'Свежая серия в конфиге'])
            ->assertStatus(201);

        // кеш сброшен обсервером, ответ собран заново — новая запись видна
        $this->apiGet('/api/config')
            ->assertOk()
            ->assertJsonFragment(['name' => 'Свежая серия в конфиге']);
    }
}
