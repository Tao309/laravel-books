<?php

namespace App\Services;

use App\Enums\ShopType;
use App\Models\Shop;

class SessionShopService
{
    public function __construct(
        private ?Shop $shop
    ) {}

    public function getShop(): ?Shop
    {
        return $this->shop;
    }

    public function setShop(Shop $shop): static
    {
        $this->shop = $shop;

        return $this;
    }

    public function isWildberriesShopType(): bool
    {
        return $this->getShop()->type === ShopType::Wildberries->value;
    }
}
