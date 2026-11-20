<?php

namespace App\Http\QueryFilters;

use App\Models\SourceProduct;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

class SourceProductQueryFilter extends AbstractQueryFilter
{
    public function applySearchFilter(Builder $query, $value): Builder
    {
        return $this->applySearchToQuery(
            $query,
            SourceProduct::PARAM_TITLE,
            $value,
            Config::get('app.t_price_checker.search_remove_words', [])
        );
    }
}
