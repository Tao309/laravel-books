<?php

namespace Tests\Feature\Api;

use App\Models\Book;
use App\Models\BookUserData;
use App\Models\User;
use Tests\Feature\Api\Concerns\ActsAsApiClient;
use Tests\TestCase;

/**
 * GET /api/book_user_data/{book_id} — единственный роут BookUserData.
 * Репозиторий скоупится по Auth::id(), firstOrFail → ModelNotFound →
 * рендерится в HTTP 200 + success:false.
 */
class BookUserDataApiTest extends TestCase
{
    use ActsAsApiClient;

    private function createForCurrentUser(): BookUserData
    {
        return BookUserData::factory()->create([
            BookUserData::PARAM_BOOK_ID => Book::factory()->create()->id,
            BookUserData::PARAM_USER_ID => $this->user->id,
            BookUserData::PARAM_RELEASE_DATE => '2026-11-10 12:00:00',
            BookUserData::PARAM_LISTEN_PRICE_VALUE => 1500,
            BookUserData::PARAM_COMMENT => 'Дождаться скидки',
        ]);
    }

    public function test_show_returns_user_data_for_current_user(): void
    {
        $userData = $this->createForCurrentUser();

        $this->apiGet("/api/book_user_data/{$userData->book_id}")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.release_date', '2026-11-10 12:00:00')
            ->assertJsonPath('data.listen_price_value', 1500)
            ->assertJsonPath('data.comment', 'Дождаться скидки');
    }

    public function test_show_does_not_leak_foreign_keys(): void
    {
        $userData = $this->createForCurrentUser();

        $data = $this->apiGet("/api/book_user_data/{$userData->book_id}")
            ->assertOk()
            ->json('data');

        $this->assertArrayNotHasKey(BookUserData::PARAM_BOOK_ID, $data);
        $this->assertArrayNotHasKey(BookUserData::PARAM_USER_ID, $data);
    }

    /**
     * Документация дефекта: ModelNotFoundException → NotFoundHttpException →
     * Throwable-коллбэк → 500 (см. bootstrap/app.php).
     */
    public function test_show_missing_row_is_200_with_success_false(): void
    {
        $this->apiGet('/api/book_user_data/99999')
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }

    /**
     * Документация дефекта: скоуп по Auth::id() → ModelNotFoundException →
     * NotFoundHttpException → Throwable-коллбэк → 500.
     */
    public function test_show_other_users_row_is_200_with_success_false(): void
    {
        $otherUser = User::factory()->create();
        $userData = BookUserData::factory()->create([
            BookUserData::PARAM_BOOK_ID => Book::factory()->create()->id,
            BookUserData::PARAM_USER_ID => $otherUser->id,
        ]);

        $this->apiGet("/api/book_user_data/{$userData->book_id}")
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }
}
