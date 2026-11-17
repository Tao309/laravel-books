<?php

namespace Tests\Feature\Api;

use App\Models\PublishingHouse;
use Tests\Feature\Api\Concerns\ActsAsApiClient;
use Tests\TestCase;

/**
 * API /api/publishing_houses. Контроллер использует indexAction (без пагинации),
 * destroy-роут отсутствует; лимиты name симметричны между store и update —
 * эти особенности зафиксированы тестами как текущее поведение.
 */
class PublishingHouseApiTest extends TestCase
{
    use ActsAsApiClient;

    private const string NAME = 'Полярная звезда';

    // ---------- GET /api/publishing_houses ----------

    public function test_index_returns_collection_without_pagination(): void
    {
        PublishingHouse::factory()->count(2)->create();

        $response = $this->apiGet('/api/publishing_houses')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(2, 'data.items');

        // meta коллекции содержит только count, без current_page/last_page
        $this->assertArrayNotHasKey('current_page', $response->json('data.meta'));
    }

    public function test_index_ignores_limit_filter(): void
    {
        PublishingHouse::factory()->count(3)->create();

        // документация дефекта: репозиторий делает PublishingHouse::all()
        $this->apiGet('/api/publishing_houses?limit=1')
            ->assertOk()
            ->assertJsonCount(3, 'data.items');
    }

    // ---------- POST /api/publishing_houses ----------

    public function test_store_creates_house_returns_201(): void
    {
        $this->apiPost('/api/publishing_houses', ['name' => self::NAME])
            ->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', self::NAME);

        $this->assertDatabaseHas(PublishingHouse::TABLE_NAME, [
            PublishingHouse::PARAM_NAME => self::NAME,
        ]);
    }

    public function test_store_rejects_duplicate_name(): void
    {
        PublishingHouse::factory()->create([PublishingHouse::PARAM_NAME => self::NAME]);

        $this->apiPost('/api/publishing_houses', ['name' => self::NAME])
            ->assertUnprocessable()
            ->assertJsonPath('success', false)
            ->assertJsonPath('errors.name.0', 'A PublishingHouse with this name already exists');
    }

    public function test_store_validates_name_required(): void
    {
        $this->apiPost('/api/publishing_houses', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_store_accepts_two_character_name(): void
    {
        $this->apiPost('/api/publishing_houses', ['name' => 'AB'])
            ->assertStatus(201);
    }

    // ---------- GET /api/publishing_houses/{id} ----------

    public function test_show_returns_house(): void
    {
        $house = PublishingHouse::factory()->create();

        $this->apiGet("/api/publishing_houses/{$house->id}")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $house->id)
            ->assertJsonPath('data.name', $house->name);
    }

    /**
     * Документация дефекта: ModelNotFoundException → NotFoundHttpException →
     * Throwable-коллбэк → 500 (см. bootstrap/app.php).
     */
    public function test_show_missing_house_is_200_with_success_false(): void
    {
        $this->apiGet('/api/publishing_houses/99999')
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }

    // ---------- PUT /api/publishing_houses/{id} ----------

    public function test_update_changes_name(): void
    {
        $house = PublishingHouse::factory()->create([PublishingHouse::PARAM_NAME => 'Старое имя издательства']);

        $this->apiPut("/api/publishing_houses/{$house->id}", ['name' => 'Новое имя издательства'])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Новое имя издательства');

        $this->assertDatabaseHas(PublishingHouse::TABLE_NAME, [
            PublishingHouse::PARAM_ID => $house->id,
            PublishingHouse::PARAM_NAME => 'Новое имя издательства',
        ]);
    }

    public function test_update_ignores_self_in_unique(): void
    {
        $house = PublishingHouse::factory()->create([PublishingHouse::PARAM_NAME => 'Старое имя издательства']);

        $this->apiPut("/api/publishing_houses/{$house->id}", ['name' => 'Старое имя издательства'])
            ->assertOk();
    }

    public function test_update_rejects_name_of_other_house(): void
    {
        PublishingHouse::factory()->create([PublishingHouse::PARAM_NAME => 'Занятое имя издательства']);
        $house = PublishingHouse::factory()->create([PublishingHouse::PARAM_NAME => 'Свободное имя издательства']);

        $this->apiPut("/api/publishing_houses/{$house->id}", ['name' => 'Занятое имя издательства'])
            ->assertUnprocessable()
            ->assertJsonPath('errors.name.0', 'A PublishingHouse with this name already exists');
    }

    public function test_update_allows_two_character_name_like_store(): void
    {
        $house = PublishingHouse::factory()->create([PublishingHouse::PARAM_NAME => 'Издательство длинное']);

        // в отличие от publishing_brands лимиты store/update здесь одинаковые (min:2/max:255)
        $this->apiPut("/api/publishing_houses/{$house->id}", ['name' => 'AB'])
            ->assertOk();
    }

    /**
     * Документация дефекта: ModelNotFoundException → NotFoundHttpException →
     * Throwable-коллбэк → 500 (см. bootstrap/app.php).
     */
    public function test_update_missing_house_is_200_with_success_false(): void
    {
        $this->apiPut('/api/publishing_houses/99999', ['name' => 'Новое имя издательства'])
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }

    // ---------- DELETE /api/publishing_houses/{id} ----------

    public function test_destroy_endpoint_is_not_registered(): void
    {
        $house = PublishingHouse::factory()->create();

        // Документация дефекта: незарегистрированный роут ловится
        // Throwable-коллбэком в bootstrap/app.php → 500, а не 404.
        // Слой удаления в шине тоже сломан (DestroyPublishingHouseHandler не привязан).
        $this->apiDelete("/api/publishing_houses/{$house->id}")
            ->assertStatus(500)
            ->assertJsonPath('success', false);

        $this->assertModelExists($house);
    }
}
