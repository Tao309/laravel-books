<?php

namespace App\Http\QueryFilters;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductQueryFilter extends AbstractQueryFilter
{
    public function __construct(
    ) {}

    public function applySearchFilter(Builder $query, $value): Builder
    {
        $titleField = Product::PARAM_TITLE;
        $value = mb_strtolower($value);

        $query->whereRaw("LOWER({$titleField}) LIKE ?", "%{$value}%");
        $query->limit(7);

        return $query;
    }
}
