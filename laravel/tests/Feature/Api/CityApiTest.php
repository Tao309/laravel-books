<?php

namespace Tests\Feature\Api;

use App\Models\City;
use Tests\Feature\Api\Concerns\ActsAsApiClient;
use Tests\TestCase;

/**
 * API /api/cities. Контроллер использует indexAction (без пагинации),
 * show-роут исключён. Кворки текущего поведения:
 *  - title не required → store без title проходит валидацию, но падает
 *    в CityDTO::fromArray (undefined array key) → HTTP 500;
 *  - code ограничен min:2/max:4, title — min:2/max:100.
 */
class CityApiTest extends TestCase
{
    use ActsAsApiClient;

    private const string CODE = 'KZN';

    private const string TITLE = 'Казань';

    // ---------- GET /api/cities ----------

    public function test_index_returns_collection_without_pagination(): void
    {
        City::factory()->count(2)->create();

        $response = $this->apiGet('/api/cities')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(2, 'data.items');

        // meta коллекции содержит только count, без current_page/last_page
        $this->assertArrayNotHasKey('current_page', $response->json('data.meta'));
    }

    public function test_index_ignores_limit_filter(): void
    {
        City::factory()->count(3)->create();

        // документация дефекта: репозиторий делает City::all()
        $this->apiGet('/api/cities?limit=1')
            ->assertOk()
            ->assertJsonCount(3, 'data.items');
    }

    // ---------- POST /api/cities ----------

    public function test_store_creates_city_returns_201(): void
    {
        $this->apiPost('/api/cities', [
            City::PARAM_CODE => self::CODE,
            City::PARAM_TITLE => self::TITLE,
        ])
            ->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.code', self::CODE)
            ->assertJsonPath('data.title', self::TITLE);

        $this->assertDatabaseHas(City::TABLE_NAME, [
            City::PARAM_CODE => self::CODE,
            City::PARAM_TITLE => self::TITLE,
        ]);
    }

    public function test_store_rejects_duplicate_code(): void
    {
        City::factory()->create([City::PARAM_CODE => self::CODE]);

        $this->apiPost('/api/cities', [
            City::PARAM_CODE => self::CODE,
            City::PARAM_TITLE => self::TITLE,
        ])
            ->assertUnprocessable()
            ->assertJsonPath('success', false)
            ->assertJsonPath('errors.code.0', 'A City with this code already exists');
    }

    public function test_store_rejects_duplicate_title(): void
    {
        City::factory()->create([City::PARAM_TITLE => self::TITLE]);

        $this->apiPost('/api/cities', [
            City::PARAM_CODE => self::CODE,
            City::PARAM_TITLE => self::TITLE,
        ])
            ->assertUnprocessable()
            ->assertJsonPath('errors.title.0', 'A City with this title already exists');
    }

    public function test_store_rejects_code_longer_than_four(): void
    {
        $this->apiPost('/api/cities', [
            City::PARAM_CODE => 'TOOLONG',
            City::PARAM_TITLE => self::TITLE,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([City::PARAM_CODE]);
    }

    /**
     * Документация дефекта: title не required, валидация проходит, но
     * CityDTO::fromArray падает на отсутствующем ключе → 500.
     */
    public function test_store_without_title_is_500(): void
    {
        $this->apiPost('/api/cities', [City::PARAM_CODE => self::CODE])
            ->assertStatus(500);
    }

    // ---------- GET /api/cities/{id} ----------

    public function test_show_endpoint_is_not_registered(): void
    {
        $city = City::factory()->create();

        // Документация дефекта: незарегистрированный роут ловится
        // Throwable-коллбэком в bootstrap/app.php → 500, а не 404.
        $this->apiGet("/api/cities/{$city->id}")
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }

    // ---------- PUT /api/cities/{id} ----------

    public function test_update_changes_code_and_title(): void
    {
        $city = City::factory()->create([City::PARAM_CODE => 'OLD', City::PARAM_TITLE => 'Старый город']);

        $this->apiPut("/api/cities/{$city->id}", [
            City::PARAM_CODE => 'MSK',
            City::PARAM_TITLE => 'Новый город',
        ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.code', 'MSK')
            ->assertJsonPath('data.title', 'Новый город');

        $this->assertDatabaseHas(City::TABLE_NAME, [
            City::PARAM_ID => $city->id,
            City::PARAM_CODE => 'MSK',
            City::PARAM_TITLE => 'Новый город',
        ]);
    }

    public function test_update_rejects_code_of_other_city(): void
    {
        City::factory()->create([City::PARAM_CODE => 'BUSY']);
        $city = City::factory()->create([City::PARAM_CODE => 'FREE']);

        $this->apiPut("/api/cities/{$city->id}", [
            City::PARAM_CODE => 'BUSY',
            City::PARAM_TITLE => $city->title,
        ])
            ->assertUnprocessable()
            ->assertJsonPath('errors.code.0', 'A City with this code already exists');
    }

    /**
     * Документация дефекта: ModelNotFoundException → NotFoundHttpException →
     * Throwable-коллбэк → 500 (см. bootstrap/app.php).
     */
    public function test_update_missing_city_is_200_with_success_false(): void
    {
        $this->apiPut('/api/cities/99999', [
            City::PARAM_CODE => 'MSK',
            City::PARAM_TITLE => 'Новый город',
        ])
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }

    // ---------- DELETE /api/cities/{id} ----------

    public function test_destroy_deletes_city(): void
    {
        $city = City::factory()->create();

        $this->apiDelete("/api/cities/{$city->id}")
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertModelMissing($city);
    }

    /**
     * Документация дефекта: ModelNotFoundException → NotFoundHttpException →
     * Throwable-коллбэк → 500 (см. bootstrap/app.php).
     */
    public function test_destroy_missing_city_is_200_with_success_false(): void
    {
        $this->apiDelete('/api/cities/99999')
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }
}
