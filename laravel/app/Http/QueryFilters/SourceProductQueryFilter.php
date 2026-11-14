<?php

namespace App\Http\QueryFilters;

use App\Models\SourceProduct;
use Illuminate\Database\Eloquent\Builder;

class SourceProductQueryFilter extends AbstractQueryFilter
{
    public function applySearchFilter(Builder $query, $value): Builder
    {
        return $this->applyTitleSearchFilter(
            $query,
            SourceProduct::PARAM_TITLE,
            $value
        );
    }
}
