<?php

namespace Tests\Feature\Api;

use App\Models\PublishingBrand;
use Tests\Feature\Api\Concerns\ActsAsApiClient;
use Tests\TestCase;

/**
 * Сквозной тест кеша справочника через /api/config:
 * PublishingBrandsCacheProvider питает book_publishing_brands,
 * а Observer сбрасывает кеш на изменениях модели.
 */
class PublishingBrandsConfigTest extends TestCase
{
    use ActsAsApiClient;

    public function test_config_contains_cached_brands(): void
    {
        $brand = PublishingBrand::factory()->create();

        $this->apiGet('/api/config')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.book_publishing_brands.0.id', $brand->id)
            ->assertJsonPath('data.book_publishing_brands.0.name', $brand->name);
    }

    public function test_store_invalidates_config_cache(): void
    {
        // первый прогрев кеша без брендов
        $this->apiGet('/api/config')->assertOk();

        $this->apiPost('/api/publishing_brands', ['name' => 'Свежий бренд в конфиге'])
            ->assertStatus(201);

        // кеш сброшен обсервером, ответ собран заново — новый бренд виден
        $this->apiGet('/api/config')
            ->assertOk()
            ->assertJsonFragment(['name' => 'Свежий бренд в конфиге']);
    }
}
