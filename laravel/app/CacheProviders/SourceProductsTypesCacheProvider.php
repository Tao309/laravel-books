<?php

declare(strict_types=1);

namespace App\CacheProviders;

use App\Models\SourceProductType;

class SourceProductsTypesCacheProvider extends CommonCacheProvider
{
    protected const string NAME = 't_source_product_types';

    protected const int EXPIRES = 3600 * 24 * 30;

    protected function generateData(): array
    {
        return SourceProductType::all(
            SourceProductType::PARAM_ID,
            SourceProductType::PARAM_CODE,
            SourceProductType::PARAM_NAME
        )->toArray();
    }
}
