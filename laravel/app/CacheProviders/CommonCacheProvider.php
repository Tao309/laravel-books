<?php

declare(strict_types=1);

namespace App\CacheProviders;

use Illuminate\Support\Facades\Cache;

abstract class CommonCacheProvider implements CacheProviderInterface
{
    protected const string NAME = 't_cache';

    protected const int EXPIRES = 3600 * 24 * 30;

    abstract protected function generateData(): mixed;

    public function get(): array
    {
        $result = Cache::remember(static::NAME, static::EXPIRES, function () {
            return json_encode($this->generateData());
        });

        return json_decode($result, true);
    }

    public function delete(): void
    {
        Cache::delete(static::NAME);
    }
}
