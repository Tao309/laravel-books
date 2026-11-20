<?php

namespace Tests\Feature\Api\Concerns;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\TestResponse;

trait ActsAsApiClient
{
    use RefreshDatabase;

    private const string X_REQUESTED_WITH = 'test-x-requested-with';

    private const string PRICE_CHECKER_ID = '12345';

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'app.auth.x_requested_with' => self::X_REQUESTED_WITH,
            'app.auth.price_checker_id' => self::PRICE_CHECKER_ID,
        ]);

        Shop::factory()->ozon()->create();

        // ozon вставлен с явным id=1 — pgsql-секвенс остался на 1 (nextval
        // нетранзакционен, его значение зависит от порядка тестов в прогоне,
        // отсюда недетерминированные дубликаты shops_pkey). Сдвигаем секвенс
        // за фактический максимум: обычные Shop::factory() получают id > 1.
        DB::statement(sprintf(
            "SELECT setval(pg_get_serial_sequence('%s', '%s'), COALESCE((SELECT MAX(%s) FROM %s), 0) + 1, false)",
            Shop::TABLE_NAME,
            Shop::PARAM_ID,
            Shop::PARAM_ID,
            Shop::TABLE_NAME,
        ));

        $this->user = User::factory()->create();
    }

    /**
     * Заголовки для прохода UserSecurity: shop-type по фабрике ozon,
     * значения из config('app.auth.*'), токен по remember_token.
     */
    private function headers(): array
    {
        return [
            'shop-type' => 'ozon',
            'x-requested-with' => self::X_REQUESTED_WITH,
            't-price-checker-id' => self::PRICE_CHECKER_ID,
            'Authorization' => 'Bearer ' . $this->user->remember_token,
        ];
    }

    private function apiGet(string $uri): TestResponse
    {
        return $this->withHeaders($this->headers())->getJson($uri);
    }

    private function apiPost(string $uri, array $data = []): TestResponse
    {
        return $this->withHeaders($this->headers())->postJson($uri, $data);
    }

    private function apiPut(string $uri, array $data = []): TestResponse
    {
        return $this->withHeaders($this->headers())->putJson($uri, $data);
    }

    private function apiDelete(string $uri): TestResponse
    {
        return $this->withHeaders($this->headers())->deleteJson($uri);
    }
}
