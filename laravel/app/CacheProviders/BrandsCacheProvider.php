<?php

declare(strict_types=1);

namespace App\CacheProviders;

use App\Models\Brand;

class BrandsCacheProvider extends CommonCacheProvider
{
    protected const string NAME = 't_brands';

    protected const int EXPIRES = 3600 * 24 * 30;

    protected function generateData(): array
    {
        return Brand::all([
            Brand::PARAM_ID,
            Brand::PARAM_CODE,
            Brand::PARAM_TITLE,
        ])
            ->sortBy(Brand::PARAM_TITLE)
            ->toArray();
    }
}
