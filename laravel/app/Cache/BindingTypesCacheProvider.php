<?php

declare(strict_types=1);

namespace App\Cache;

use App\Models\BindingType;

class BindingTypesCacheProvider extends CommonCacheProvider
{
    protected const string NAME = 't_binding_types';

    protected const int EXPIRES = 3600 * 24 * 30;

    protected function generateData(): array
    {
        return BindingType::all()->toArray();
    }
}
