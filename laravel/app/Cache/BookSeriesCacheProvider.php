<?php

declare(strict_types=1);

namespace App\Cache;

use App\Models\BookSeries;

class BookSeriesCacheProvider extends CommonCacheProvider
{
    protected const string NAME = 't_book_series';

    protected const int EXPIRES = 3600 * 24 * 30;

    protected function generateData(): array
    {
        return BookSeries::all()->toArray();
    }
}
