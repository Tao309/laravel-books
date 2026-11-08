<?php

namespace App\Http\Middleware;

use App\Auth\CustomUserProvider;
use App\Cache\ShopsCacheProvider;
use App\Services\SessionShopService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

readonly class UserSecurity
{
    public function __construct(
        private ShopsCacheProvider $shopCacheProvider,
        private SessionShopService $sessionShopService
    ) {}

    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $shopType = $request->header('shop-type');
        $requestedWith = $request->header('x-requested-with');
        $priceCheckerId = $request->header('t-price-checker-id');

        $shop = $this->shopCacheProvider->getShopByType($shopType);

        if (empty($shop)) {
            throw new AccessDeniedHttpException('Missing or not correct required shop type');
        }

        $envRequestedWith = Config::get('app.auth.x_requested_with');
        $envPriceCheckerId = Config::get('app.auth.price_checker_id');

        if (empty($requestedWith) || empty($priceCheckerId)
            || empty($envRequestedWith) || empty($envPriceCheckerId)
            || $requestedWith !== $envRequestedWith
            || $priceCheckerId != $envPriceCheckerId
        ) {
            throw new AccessDeniedHttpException('Missing required headers values');
        }

        $this->sessionShopService->setShop($shop);

        Auth::attempt([CustomUserProvider::TOKEN => $request->bearerToken()], true);

        if (! Auth::check()) {
            throw new AccessDeniedHttpException('Access denied, please authenticate first');
        }

        return $next($request);
    }
}
