<?php

namespace Http\Middleware;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserSecurityTest extends TestCase
{
    use RefreshDatabase;

    private const string X_REQUESTED_WITH = 'test-x-requested-with';

    private const string PRICE_CHECKER_ID = '12345';

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'app.auth.x_requested_with' => self::X_REQUESTED_WITH,
            'app.auth.price_checker_id' => self::PRICE_CHECKER_ID,
        ]);

        Shop::factory()->ozon()->create();
    }

    public function test_all_headers_and_token_pass(): void
    {
        $this
            ->withHeaders($this->headers())
            ->getJson('/api/shops')
            ->assertOk()
            ->assertJsonPath('success', true);
    }

    public function test_missing_shop_type_header_is_rejected(): void
    {
        $this->assertRejected(
            $this->headers(shopType: null),
            'Missing or not correct required shop type',
        );
    }

    public function test_unknown_shop_type_is_rejected(): void
    {
        $this->assertRejected(
            $this->headers(shopType: 'unknown-shop'),
            'Missing or not correct required shop type',
        );
    }

    public function test_missing_x_requested_with_is_rejected(): void
    {
        $this->assertRejected(
            $this->headers(requestedWith: null),
            'Missing required headers values',
        );
    }

    public function test_wrong_x_requested_with_is_rejected(): void
    {
        $this->assertRejected(
            $this->headers(requestedWith: 'wrong-value'),
            'Missing required headers values',
        );
    }

    public function test_missing_price_checker_id_is_rejected(): void
    {
        $this->assertRejected(
            $this->headers(priceCheckerId: null),
            'Missing required headers values',
        );
    }

    public function test_wrong_price_checker_id_is_rejected(): void
    {
        $this->assertRejected(
            $this->headers(priceCheckerId: '99999'),
            'Missing required headers values',
        );
    }

    public function test_empty_config_x_requested_with_is_rejected(): void
    {
        config(['app.auth.x_requested_with' => null]);

        $this->assertRejected(
            $this->headers(),
            'Missing required headers values',
        );
    }

    public function test_empty_config_price_checker_id_is_rejected(): void
    {
        config(['app.auth.price_checker_id' => null]);

        $this->assertRejected(
            $this->headers(),
            'Missing required headers values',
        );
    }

    public function test_missing_bearer_token_is_rejected(): void
    {
        $this->assertRejected(
            $this->headers(token: null),
            'Access denied, please authenticate first',
        );
    }

    public function test_wrong_bearer_token_is_rejected(): void
    {
        $this->assertRejected(
            $this->headers(token: 'not-a-real-token'),
            'Access denied, please authenticate first',
        );
    }

    private function headers(
        ?string $shopType = 'ozon',
        ?string $requestedWith = self::X_REQUESTED_WITH,
        ?string $priceCheckerId = self::PRICE_CHECKER_ID,
        ?string $token = 'auto',
    ): array {
        $user = User::factory()->create();

        return array_filter([
            'shop-type' => $shopType,
            'x-requested-with' => $requestedWith,
            't-price-checker-id' => $priceCheckerId,
            'Authorization' => $token === null
                ? null
                : 'Bearer ' . ($token === 'auto' ? $user->remember_token : $token),
        ]);
    }

    private function assertRejected(array $headers, string $message): void
    {
        $this
            ->withHeaders($headers)
            ->getJson('/api/shops')
            ->assertStatus(500)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', $message);
    }
}
