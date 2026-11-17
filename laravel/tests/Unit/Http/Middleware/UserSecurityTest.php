<?php

namespace Tests\Unit\Http\Middleware;

use App\CacheProviders\ShopsCacheProvider;
use App\Http\Middleware\UserSecurity;
use App\Models\Shop;
use App\Models\User;
use App\Services\SessionShopService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Tests\TestCase;

class UserSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'app.auth.x_requested_with' => 'test-x-requested-with',
            'app.auth.price_checker_id' => '12345',
        ]);

        Shop::factory()->ozon()->create();
    }

    public function test_sets_session_shop_and_authenticates_user(): void
    {
        $user = User::factory()->create();
        $shopId = Shop::query()->where(Shop::PARAM_TYPE, 'ozon')->value(Shop::PARAM_ID);

        $reached = false;

        $this->middleware()->handle(
            $this->request(token: $user->remember_token),
            function () use (&$reached) {
                $reached = true;

                return response()->json();
            }
        );

        $this->assertTrue($reached);
        $this->assertSame($shopId, SessionShopService::getShopId());
        $this->assertTrue(Auth::check());
    }

    public function test_throws_when_shop_type_unknown(): void
    {
        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage('Missing or not correct required shop type');

        $this->middleware()->handle(
            $this->request(shopType: 'unknown-shop'),
            fn () => response()->json(),
        );
    }

    public function test_throws_when_no_user_by_token(): void
    {
        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage('Access denied, please authenticate first');

        $this->middleware()->handle(
            $this->request(token: 'not-a-real-token'),
            fn () => response()->json(),
        );
    }

    private function middleware(): UserSecurity
    {
        return new UserSecurity(
            $this->app->make(ShopsCacheProvider::class),
            $this->app->make(SessionShopService::class),
        );
    }

    private function request(
        string $shopType = 'ozon',
        string $requestedWith = 'test-x-requested-with',
        string $priceCheckerId = '12345',
        ?string $token = null,
    ): Request {
        $request = Request::create('/api/products', 'GET');
        $request->headers->set('shop-type', $shopType);
        $request->headers->set('x-requested-with', $requestedWith);
        $request->headers->set('t-price-checker-id', $priceCheckerId);

        if ($token !== null) {
            $request->headers->set('Authorization', 'Bearer '.$token);
        }

        return $request;
    }
}
