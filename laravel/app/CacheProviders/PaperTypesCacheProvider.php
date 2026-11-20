<?php

declare(strict_types=1);

namespace App\CacheProviders;

use App\Models\PaperType;

class PaperTypesCacheProvider extends CommonCacheProvider
{
    protected const string NAME = 't_paper_types';

    protected const int EXPIRES = 3600 * 24 * 30;

    protected function generateData(): array
    {
        return PaperType::all(PaperType::PARAM_ID, PaperType::PARAM_LABEL, PaperType::PARAM_DESCRIPTION)->toArray();
    }
}
