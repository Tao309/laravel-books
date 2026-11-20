<?php

namespace App\Http\QueryFilters;

use App\Models\Book;
use Illuminate\Database\Eloquent\Builder;

class BookQueryFilter extends AbstractQueryFilter
{
    public function applySearchFilter(Builder $query, $value): Builder
    {
        return $this->applySearchToQuery(
            $query,
            Book::PARAM_TITLE,
            $value
        );
    }
}
