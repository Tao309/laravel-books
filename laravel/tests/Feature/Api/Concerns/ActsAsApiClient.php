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

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'app.t_price_checker.auth.x_requested_with' => self::X_REQUESTED_WITH,
            'app.t_price_checker.auth.price_checker_id' => self::PRICE_CHECKER_ID,
        ]);

        Shop::factory()->ozon()->create();
        $this->syncShopSequence();

        $this->user = User::factory()->create();
    }

    private function syncShopSequence(): void
    {
        // MySQL: ALTER TABLE ... AUTO_INCREMENT делал бы неявный commit внутри
        // транзакции RefreshDatabase и ломал вложенные savepoint'ы.
        $table = Shop::TABLE_NAME;
        $id = Shop::PARAM_ID;
        $driver = DB::connection()->getDriverName();

        if ($driver !== 'pgsql') {
            return;
        }

        DB::statement(sprintf(
            "SELECT setval(pg_get_serial_sequence('%s', '%s'), COALESCE((SELECT MAX(%s) FROM %s), 0) + 1, false)",
            $table, $id, $id, $table
        ));
    }

    protected function apiHeaders(): array
    {
        return [
            'shop-type' => 'ozon',
            'x-requested-with' => self::X_REQUESTED_WITH,
            't-price-checker-id' => self::PRICE_CHECKER_ID,
            'Authorization' => 'Bearer ' . $this->user->remember_token,
        ];
    }

    protected function apiGet(string $uri): TestResponse
    {
        return $this->withHeaders($this->apiHeaders())->getJson($uri);
    }

    protected function apiPost(string $uri, array $data = []): TestResponse
    {
        return $this->withHeaders($this->apiHeaders())->postJson($uri, $data);
    }

    protected function apiPut(string $uri, array $data = []): TestResponse
    {
        return $this->withHeaders($this->apiHeaders())->putJson($uri, $data);
    }

    protected function apiDelete(string $uri): TestResponse
    {
        return $this->withHeaders($this->apiHeaders())->deleteJson($uri);
    }
}
