<?php

namespace Tests\Feature\Api;

use App\Models\SourceProductType;
use Tests\Feature\Api\Concerns\ActsAsApiClient;
use Tests\TestCase;

/**
 * Сквозной тест кеша справочника через /api/config:
 * SourceProductsTypesCacheProvider питает source_product_types,
 * а Observer сбрасывает кеш на изменениях модели.
 */
class SourceProductTypesConfigTest extends TestCase
{
    use ActsAsApiClient;

    public function test_config_contains_cached_types(): void
    {
        $type = SourceProductType::factory()->create();

        $this->apiGet('/api/config')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.source_product_types.0.id', $type->id)
            ->assertJsonPath('data.source_product_types.0.code', $type->code)
            ->assertJsonPath('data.source_product_types.0.name', $type->name);
    }

    public function test_store_invalidates_config_cache(): void
    {
        // первый прогрев кеша без типов
        $this->apiGet('/api/config')->assertOk();

        $this->apiPost('/api/source_product_types', [
            SourceProductType::PARAM_CODE => 'fresh-code',
            SourceProductType::PARAM_NAME => 'Свежий тип в конфиге',
        ])
            ->assertStatus(201);

        // кеш сброшен обсервером, ответ собран заново — новый тип виден
        $this->apiGet('/api/config')
            ->assertOk()
            ->assertJsonFragment(['name' => 'Свежий тип в конфиге']);
    }
}
