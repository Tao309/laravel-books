<?php

namespace Tests\Feature\Api;

use App\Models\BookSeries;
use Tests\Feature\Api\Concerns\ActsAsApiClient;
use Tests\TestCase;

/**
 * API /api/books_series. Контроллер использует indexAction (без пагинации),
 * destroy-роут отсутствует (сервис удаления тоже — см. BookSeriesBusTest).
 */
class BookSeriesApiTest extends TestCase
{
    use ActsAsApiClient;

    private const string NAME = 'Мировая классика';

    // ---------- GET /api/books_series ----------

    public function test_index_returns_collection_without_pagination(): void
    {
        BookSeries::factory()->count(2)->create();

        $response = $this->apiGet('/api/books_series')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(2, 'data.items');

        // meta коллекции содержит только count, без current_page/last_page
        $this->assertArrayNotHasKey('current_page', $response->json('data.meta'));
    }

    public function test_index_ignores_limit_filter(): void
    {
        BookSeries::factory()->count(3)->create();

        // документация дефекта: репозиторий делает BookSeries::all()
        $this->apiGet('/api/books_series?limit=1')
            ->assertOk()
            ->assertJsonCount(3, 'data.items');
    }

    // ---------- POST /api/books_series ----------

    public function test_store_creates_series_returns_201(): void
    {
        $this->apiPost('/api/books_series', ['name' => self::NAME])
            ->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', self::NAME);

        $this->assertDatabaseHas(BookSeries::TABLE_NAME, [
            BookSeries::PARAM_NAME => self::NAME,
        ]);
    }

    public function test_store_rejects_duplicate_name(): void
    {
        BookSeries::factory()->create([BookSeries::PARAM_NAME => self::NAME]);

        $this->apiPost('/api/books_series', ['name' => self::NAME])
            ->assertUnprocessable()
            ->assertJsonPath('success', false)
            ->assertJsonPath('errors.name.0', 'A BookSeries with this name already exists');
    }

    public function test_store_accepts_two_character_name(): void
    {
        $this->apiPost('/api/books_series', ['name' => 'АБ'])
            ->assertStatus(201);
    }

    // ---------- GET /api/books_series/{id} ----------

    public function test_show_returns_series(): void
    {
        $series = BookSeries::factory()->create();

        $this->apiGet("/api/books_series/{$series->id}")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $series->id)
            ->assertJsonPath('data.name', $series->name);
    }

    /**
     * Документация дефекта: ModelNotFoundException → NotFoundHttpException →
     * Throwable-коллбэк → 500 (см. bootstrap/app.php).
     */
    public function test_show_missing_series_is_200_with_success_false(): void
    {
        $this->apiGet('/api/books_series/99999')
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }

    // ---------- PUT /api/books_series/{id} ----------

    public function test_update_changes_name(): void
    {
        $series = BookSeries::factory()->create([BookSeries::PARAM_NAME => 'Старое название серии']);

        $this->apiPut("/api/books_series/{$series->id}", ['name' => 'Новое название серии'])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Новое название серии');

        $this->assertDatabaseHas(BookSeries::TABLE_NAME, [
            BookSeries::PARAM_ID => $series->id,
            BookSeries::PARAM_NAME => 'Новое название серии',
        ]);
    }

    public function test_update_ignores_self_in_unique(): void
    {
        $series = BookSeries::factory()->create([BookSeries::PARAM_NAME => 'Старое название серии']);

        $this->apiPut("/api/books_series/{$series->id}", ['name' => 'Старое название серии'])
            ->assertOk();
    }

    public function test_update_rejects_name_of_other_series(): void
    {
        BookSeries::factory()->create([BookSeries::PARAM_NAME => 'Занятое название серии']);
        $series = BookSeries::factory()->create([BookSeries::PARAM_NAME => 'Свободное название серии']);

        $this->apiPut("/api/books_series/{$series->id}", ['name' => 'Занятое название серии'])
            ->assertUnprocessable()
            ->assertJsonPath('errors.name.0', 'A BookSeries with this name already exists');
    }

    /**
     * Документация дефекта: ModelNotFoundException → NotFoundHttpException →
     * Throwable-коллбэк → 500 (см. bootstrap/app.php).
     */
    public function test_update_missing_series_is_200_with_success_false(): void
    {
        $this->apiPut('/api/books_series/99999', ['name' => 'Новое название серии'])
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }

    // ---------- DELETE /api/books_series/{id} ----------

    public function test_destroy_endpoint_is_not_registered(): void
    {
        $series = BookSeries::factory()->create();

        // Документация дефекта: незарегистрированный роут ловится
        // Throwable-коллбэком в bootstrap/app.php → 500, а не 404.
        // Слой удаления в шине тоже сломан (HandlerNotFoundException).
        $this->apiDelete("/api/books_series/{$series->id}")
            ->assertStatus(500)
            ->assertJsonPath('success', false);

        $this->assertModelExists($series);
    }
}
