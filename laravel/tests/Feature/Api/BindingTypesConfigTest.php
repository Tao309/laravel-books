<?php

namespace Tests\Feature\Api;

use App\Models\BindingType;
use Tests\Feature\Api\Concerns\ActsAsApiClient;
use Tests\TestCase;

/**
 * Сквозной тест кеша справочника через /api/config:
 * BindingTypesCacheProvider питает book_binding_types,
 * а Observer сбрасывает кеш на изменениях модели.
 */
class BindingTypesConfigTest extends TestCase
{
    use ActsAsApiClient;

    public function test_config_contains_cached_binding_types(): void
    {
        $bindingType = BindingType::factory()->create();

        $this->apiGet('/api/config')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.book_binding_types.0.id', $bindingType->id)
            ->assertJsonPath('data.book_binding_types.0.label', $bindingType->label);
    }

    public function test_store_invalidates_config_cache(): void
    {
        // первый прогрев кеша без переплётов
        $this->apiGet('/api/config')->assertOk();

        $this->apiPost('/api/binding_types', ['label' => 'Свежий переплёт в конфиге'])
            ->assertStatus(201);

        // кеш сброшен обсервером, ответ собран заново — новая запись видна
        $this->apiGet('/api/config')
            ->assertOk()
            ->assertJsonFragment(['label' => 'Свежий переплёт в конфиге']);
    }
}
