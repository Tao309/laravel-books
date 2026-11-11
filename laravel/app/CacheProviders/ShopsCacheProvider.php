<?php

declare(strict_types=1);

namespace App\CacheProviders;

use App\Models\Shop;

class ShopsCacheProvider extends CommonCacheProvider
{
    protected const string NAME = 't_shops';

    protected const int EXPIRES = 3600 * 24 * 30;

    public function getShopIdByType(?string $shopType): ?int
    {
        if (! $shopType) {
            return null;
        }

        $validShopId = null;

        foreach ($this->get() as $shopData) {
            if ($shopType && $shopData[Shop::PARAM_TYPE] === $shopType) {
                $validShopId = $shopData[Shop::PARAM_ID];
                break;
            }
        }

        return $validShopId;
    }

    public function getShopByType(?string $shopType): ?Shop
    {
        if (! $shopType) {
            return null;
        }

        $validShopData = null;

        foreach ($this->get() as $shopData) {
            if ($shopType && $shopData[Shop::PARAM_TYPE] === $shopType) {
                $validShopData = $shopData;
                break;
            }
        }

        if (! $validShopData) {
            return null;
        }

        return new Shop([
            Shop::PARAM_DOMAIN => $validShopData['domain'],
            Shop::PARAM_TYPE => $validShopData['type'],
            Shop::PARAM_URL => $validShopData['url'],
        ])->setAttribute(Shop::PARAM_ID, $validShopData['id']);
    }

    protected function generateData(): array
    {
        return Shop::all([Shop::PARAM_ID, Shop::PARAM_DOMAIN, Shop::PARAM_TYPE, Shop::PARAM_URL])
            ->toArray();
    }
}
