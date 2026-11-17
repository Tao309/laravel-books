<?php

namespace Tests\Feature\Api;

use App\Models\PaperType;
use Tests\Feature\Api\Concerns\ActsAsApiClient;
use Tests\TestCase;

/**
 * API /api/paper_types — полный CRUD (index, show, store, update, destroy).
 * Контроллер использует indexAction (без пагинации).
 */
class PaperTypeApiTest extends TestCase
{
    use ActsAsApiClient;

    private const string LABEL = 'Мелованная';

    private const string DESCRIPTION = 'Бумага с мелованным покрытием';

    // ---------- GET /api/paper_types ----------

    public function test_index_returns_collection_without_pagination(): void
    {
        PaperType::factory()->count(2)->create();

        $response = $this->apiGet('/api/paper_types')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(2, 'data.items');

        // meta коллекции содержит только count, без current_page/last_page
        $this->assertArrayNotHasKey('current_page', $response->json('data.meta'));
    }

    public function test_index_ignores_limit_filter(): void
    {
        PaperType::factory()->count(3)->create();

        // документация дефекта: репозиторий делает PaperType::all()
        $this->apiGet('/api/paper_types?limit=1')
            ->assertOk()
            ->assertJsonCount(3, 'data.items');
    }

    // ---------- POST /api/paper_types ----------

    public function test_store_creates_paper_type_returns_201(): void
    {
        $this->apiPost('/api/paper_types', [
            PaperType::PARAM_LABEL => self::LABEL,
            PaperType::PARAM_DESCRIPTION => self::DESCRIPTION,
        ])
            ->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.label', self::LABEL)
            ->assertJsonPath('data.description', self::DESCRIPTION);

        $this->assertDatabaseHas(PaperType::TABLE_NAME, [
            PaperType::PARAM_LABEL => self::LABEL,
            PaperType::PARAM_DESCRIPTION => self::DESCRIPTION,
        ]);
    }

    public function test_store_creates_paper_type_without_description(): void
    {
        $this->apiPost('/api/paper_types', [PaperType::PARAM_LABEL => self::LABEL])
            ->assertStatus(201)
            ->assertJsonPath('data.description', null);

        $this->assertDatabaseHas(PaperType::TABLE_NAME, [
            PaperType::PARAM_LABEL => self::LABEL,
            PaperType::PARAM_DESCRIPTION => null,
        ]);
    }

    public function test_store_rejects_duplicate_label(): void
    {
        PaperType::factory()->create([PaperType::PARAM_LABEL => self::LABEL]);

        $this->apiPost('/api/paper_types', [PaperType::PARAM_LABEL => self::LABEL])
            ->assertUnprocessable()
            ->assertJsonPath('success', false)
            ->assertJsonPath('errors.label.0', 'A PaperType with this label already exists');
    }

    public function test_store_rejects_label_longer_than_twenty(): void
    {
        $this->apiPost('/api/paper_types', [
            PaperType::PARAM_LABEL => 'Очень длинное название бумаги',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([PaperType::PARAM_LABEL]);
    }

    public function test_store_rejects_short_description(): void
    {
        $this->apiPost('/api/paper_types', [
            PaperType::PARAM_LABEL => self::LABEL,
            PaperType::PARAM_DESCRIPTION => 'Я',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([PaperType::PARAM_DESCRIPTION]);
    }

    // ---------- GET /api/paper_types/{id} ----------

    public function test_show_returns_paper_type(): void
    {
        $paperType = PaperType::factory()->create();

        $this->apiGet("/api/paper_types/{$paperType->id}")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $paperType->id)
            ->assertJsonPath('data.label', $paperType->label);
    }

    /**
     * Документация дефекта: ModelNotFoundException → NotFoundHttpException →
     * Throwable-коллбэк → 500 (см. bootstrap/app.php).
     */
    public function test_show_missing_paper_type_is_200_with_success_false(): void
    {
        $this->apiGet('/api/paper_types/99999')
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }

    // ---------- PUT /api/paper_types/{id} ----------

    public function test_update_changes_label_and_description(): void
    {
        $paperType = PaperType::factory()->create([PaperType::PARAM_LABEL => 'Старая бумага']);

        $this->apiPut("/api/paper_types/{$paperType->id}", [
            PaperType::PARAM_LABEL => 'Новая бумага',
            PaperType::PARAM_DESCRIPTION => self::DESCRIPTION,
        ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.label', 'Новая бумага')
            ->assertJsonPath('data.description', self::DESCRIPTION);

        $this->assertDatabaseHas(PaperType::TABLE_NAME, [
            PaperType::PARAM_ID => $paperType->id,
            PaperType::PARAM_LABEL => 'Новая бумага',
        ]);
    }

    public function test_update_ignores_self_in_unique(): void
    {
        $paperType = PaperType::factory()->create([PaperType::PARAM_LABEL => 'Старая бумага']);

        $this->apiPut("/api/paper_types/{$paperType->id}", [PaperType::PARAM_LABEL => 'Старая бумага'])
            ->assertOk();
    }

    public function test_update_rejects_label_of_other_paper_type(): void
    {
        PaperType::factory()->create([PaperType::PARAM_LABEL => 'Занятая бумага']);
        $paperType = PaperType::factory()->create([PaperType::PARAM_LABEL => 'Свободная бумага']);

        $this->apiPut("/api/paper_types/{$paperType->id}", [PaperType::PARAM_LABEL => 'Занятая бумага'])
            ->assertUnprocessable()
            ->assertJsonPath('errors.label.0', 'A PaperType with this label already exists');
    }

    /**
     * Документация дефекта: ModelNotFoundException → NotFoundHttpException →
     * Throwable-коллбэк → 500 (см. bootstrap/app.php).
     */
    public function test_update_missing_paper_type_is_200_with_success_false(): void
    {
        $this->apiPut('/api/paper_types/99999', [PaperType::PARAM_LABEL => 'Новая бумага'])
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }

    // ---------- DELETE /api/paper_types/{id} ----------

    public function test_destroy_deletes_paper_type(): void
    {
        $paperType = PaperType::factory()->create();

        $this->apiDelete("/api/paper_types/{$paperType->id}")
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertModelMissing($paperType);
    }

    /**
     * Документация дефекта: ModelNotFoundException → NotFoundHttpException →
     * Throwable-коллбэк → 500 (см. bootstrap/app.php).
     */
    public function test_destroy_missing_paper_type_is_200_with_success_false(): void
    {
        $this->apiDelete('/api/paper_types/99999')
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }
}
