<?php

namespace Tests\Feature\Http\Middleware;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_rejects_missing_headers(): void
    {
        Shop::factory()->ozon()->create();
        $status = $this->getJson('/api/books', ['shop-type' => 'ozon'])->status();
        $this->assertContains($status, [401, 403, 500]);
    }

    public function test_allows_valid_headers(): void
    {
        Shop::factory()->ozon()->create();
        $user = User::factory()->create();
        config([
            'app.t_price_checker.auth.x_requested_with' => 'x',
            'app.t_price_checker.auth.price_checker_id' => '1',
        ]);

        $this->withHeaders([
            'shop-type' => 'ozon',
            'x-requested-with' => 'x',
            't-price-checker-id' => '1',
            'Authorization' => 'Bearer ' . $user->remember_token,
        ])->getJson('/api/books')->assertOk();
    }
}
