<?php

namespace App\Http\QueryFilters;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Builder;

class BrandQueryFilter extends AbstractQueryFilter
{
    protected function applySearchFilter(Builder $query, $value): Builder
    {
        return $this->applySearchToQuery(
            $query,
            Brand::PARAM_TITLE,
            $value
        );
    }
}
