<?php

declare(strict_types=1);

namespace App\Cache;

use App\Models\PublishingBrand;

class PublishingBrandsCacheProvider extends CommonCacheProvider
{
    protected const string NAME = 't_publishing_brands';

    protected const int EXPIRES = 3600 * 24 * 30;

    protected function generateData(): array
    {
        return PublishingBrand::all()->toArray();
    }
}
