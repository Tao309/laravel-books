<?php

declare(strict_types=1);

namespace App\CacheProviders;

use App\Models\PublishingBrand;

class PublishingBrandsCacheProvider extends CommonCacheProvider
{
    protected const string NAME = 't_publishing_brands';

    protected const int EXPIRES = 3600 * 24 * 30;

    protected function generateData(): array
    {
        return PublishingBrand::all(PublishingBrand::PARAM_ID, PublishingBrand::PARAM_NAME)->toArray();
    }
}
