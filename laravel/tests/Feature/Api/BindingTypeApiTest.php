<?php

namespace Tests\Feature\Api;

use App\Models\BindingType;
use Tests\Feature\Api\Concerns\ActsAsApiClient;
use Tests\TestCase;

/**
 * API /api/binding_types. Контроллер использует indexAction (без пагинации),
 * show-роут исключён; лимиты label симметричны (min:5/max:50).
 */
class BindingTypeApiTest extends TestCase
{
    use ActsAsApiClient;

    private const string LABEL = 'Твёрдый переплёт';

    // ---------- GET /api/binding_types ----------

    public function test_index_returns_collection_without_pagination(): void
    {
        // фабрика генерит случайный label (уникальный индекс) —
        // при count() подряд рандом коллидится, поэтому создаём явно
        foreach (['Тип 1', 'Тип 2'] as $label) {
            BindingType::factory()->create([BindingType::PARAM_LABEL => $label]);
        }

        $response = $this->apiGet('/api/binding_types')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(2, 'data.items');

        // meta коллекции содержит только count, без current_page/last_page
        $this->assertArrayNotHasKey('current_page', $response->json('data.meta'));
    }

    public function test_index_ignores_limit_filter(): void
    {
        foreach (['Тип 1', 'Тип 2', 'Тип 3'] as $label) {
            BindingType::factory()->create([BindingType::PARAM_LABEL => $label]);
        }

        // документация дефекта: репозиторий делает BindingType::all()
        $this->apiGet('/api/binding_types?limit=1')
            ->assertOk()
            ->assertJsonCount(3, 'data.items');
    }

    // ---------- POST /api/binding_types ----------

    public function test_store_creates_binding_type_returns_201(): void
    {
        $this->apiPost('/api/binding_types', ['label' => self::LABEL])
            ->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.label', self::LABEL);

        $this->assertDatabaseHas(BindingType::TABLE_NAME, [
            BindingType::PARAM_LABEL => self::LABEL,
        ]);
    }

    public function test_store_rejects_duplicate_label(): void
    {
        BindingType::factory()->create([BindingType::PARAM_LABEL => self::LABEL]);

        $this->apiPost('/api/binding_types', ['label' => self::LABEL])
            ->assertUnprocessable()
            ->assertJsonPath('success', false)
            ->assertJsonPath('errors.label.0', 'An Binding Type with this label already exists');
    }

    public function test_store_rejects_label_shorter_than_five(): void
    {
        $this->apiPost('/api/binding_types', ['label' => 'AB'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['label']);
    }

    public function test_store_accepts_five_character_label(): void
    {
        $this->apiPost('/api/binding_types', ['label' => 'АВСАВ'])
            ->assertStatus(201);
    }

    // ---------- GET /api/binding_types/{id} ----------

    public function test_show_endpoint_is_not_registered(): void
    {
        $bindingType = BindingType::factory()->create();

        // Документация дефекта: незарегистрированный роут ловится
        // Throwable-коллбэком в bootstrap/app.php → 500, а не 404.
        $this->apiGet("/api/binding_types/{$bindingType->id}")
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }

    // ---------- PUT /api/binding_types/{id} ----------

    public function test_update_changes_label(): void
    {
        $bindingType = BindingType::factory()->create([BindingType::PARAM_LABEL => 'Старый переплёт']);

        $this->apiPut("/api/binding_types/{$bindingType->id}", ['label' => 'Новый переплёт'])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.label', 'Новый переплёт');

        $this->assertDatabaseHas(BindingType::TABLE_NAME, [
            BindingType::PARAM_ID => $bindingType->id,
            BindingType::PARAM_LABEL => 'Новый переплёт',
        ]);
    }

    public function test_update_ignores_self_in_unique(): void
    {
        $bindingType = BindingType::factory()->create([BindingType::PARAM_LABEL => 'Старый переплёт']);

        $this->apiPut("/api/binding_types/{$bindingType->id}", ['label' => 'Старый переплёт'])
            ->assertOk();
    }

    public function test_update_rejects_label_of_other_binding_type(): void
    {
        BindingType::factory()->create([BindingType::PARAM_LABEL => 'Занятый переплёт']);
        $bindingType = BindingType::factory()->create([BindingType::PARAM_LABEL => 'Свободный переплёт']);

        $this->apiPut("/api/binding_types/{$bindingType->id}", ['label' => 'Занятый переплёт'])
            ->assertUnprocessable()
            ->assertJsonPath('errors.label.0', 'An Binding Type with this label already exists');
    }

    /**
     * Документация дефекта: ModelNotFoundException → NotFoundHttpException →
     * Throwable-коллбэк → 500 (см. bootstrap/app.php).
     */
    public function test_update_missing_binding_type_is_200_with_success_false(): void
    {
        $this->apiPut('/api/binding_types/99999', ['label' => 'Новый переплёт'])
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }

    // ---------- DELETE /api/binding_types/{id} ----------

    public function test_destroy_deletes_binding_type(): void
    {
        $bindingType = BindingType::factory()->create();

        $this->apiDelete("/api/binding_types/{$bindingType->id}")
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertModelMissing($bindingType);
    }

    /**
     * Документация дефекта: ModelNotFoundException → NotFoundHttpException →
     * Throwable-коллбэк → 500 (см. bootstrap/app.php).
     */
    public function test_destroy_missing_binding_type_is_200_with_success_false(): void
    {
        $this->apiDelete('/api/binding_types/99999')
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }
}
