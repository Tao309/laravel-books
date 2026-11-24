<?php

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\UserSecurity;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Tests\TestCase;

class UserSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_rejects_missing_shop(): void
    {
        $mw = $this->app->make(UserSecurity::class);
        $this->expectException(AccessDeniedHttpException::class);
        $mw->handle(Request::create('/api/books', 'GET'), fn () => response()->json(['ok' => true]));
    }

    public function test_allows_valid_request(): void
    {
        Shop::factory()->ozon()->create();
        $user = User::factory()->create();
        config([
            'app.t_price_checker.auth.x_requested_with' => 'x',
            'app.t_price_checker.auth.price_checker_id' => '1',
        ]);

        $request = Request::create('/api/books', 'GET');
        $request->headers->set('shop-type', 'ozon');
        $request->headers->set('x-requested-with', 'x');
        $request->headers->set('t-price-checker-id', '1');
        $request->headers->set('Authorization', 'Bearer ' . $user->remember_token);

        $response = $this->app->make(UserSecurity::class)->handle($request, fn () => response()->json(['ok' => true]));

        $this->assertSame(200, $response->getStatusCode());
    }
}
