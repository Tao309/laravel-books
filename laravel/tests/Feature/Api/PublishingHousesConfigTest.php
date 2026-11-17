<?php

namespace Tests\Feature\Api;

use App\Models\PublishingHouse;
use Tests\Feature\Api\Concerns\ActsAsApiClient;
use Tests\TestCase;

/**
 * Сквозной тест кеша справочника через /api/config:
 * PublishingHousesCacheProvider питает book_publishing_houses,
 * а Observer сбрасывает кеш на изменениях модели.
 */
class PublishingHousesConfigTest extends TestCase
{
    use ActsAsApiClient;

    public function test_config_contains_cached_houses(): void
    {
        $house = PublishingHouse::factory()->create();

        $this->apiGet('/api/config')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.book_publishing_houses.0.id', $house->id)
            ->assertJsonPath('data.book_publishing_houses.0.name', $house->name);
    }

    public function test_store_invalidates_config_cache(): void
    {
        // первый прогрев кеша без издательств
        $this->apiGet('/api/config')->assertOk();

        $this->apiPost('/api/publishing_houses', ['name' => 'Свежее издательство в конфиге'])
            ->assertStatus(201);

        // кеш сброшен обсервером, ответ собран заново — новая запись видна
        $this->apiGet('/api/config')
            ->assertOk()
            ->assertJsonFragment(['name' => 'Свежее издательство в конфиге']);
    }
}
