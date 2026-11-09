<?php

declare(strict_types=1);

namespace App\CacheProviders;

use App\Models\PublishingHouse;

class PublishingHousesCacheProvider extends CommonCacheProvider
{
    protected const string NAME = 't_publishing_houses';

    protected const int EXPIRES = 3600 * 24 * 30;

    protected function generateData(): array
    {
        return PublishingHouse::all(PublishingHouse::PARAM_ID, PublishingHouse::PARAM_NAME)->toArray();
    }
}
