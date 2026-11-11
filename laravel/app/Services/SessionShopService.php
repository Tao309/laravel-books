<?php

namespace App\Services;

use App\Enums\ShopType;
use App\Models\Shop;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class SessionShopService
{
    public function __construct(
        private ?Shop $shop = null
    ) {}

    public static function getShopId(): int
    {
        $instance = App::make(SessionShopService::class);

        if (! $instance->shop || ! $instance->shop->id) {
            throw new AccessDeniedHttpException('Shop for session is not set');
        }

        return $instance->shop->id;
    }

    public function setShop(Shop $shop): static
    {
        $this->shop = $shop;

        return $this;
    }

    public function isWildberriesShopType(): bool
    {
        return $this->shop->type === ShopType::Wildberries->value;
    }
}
