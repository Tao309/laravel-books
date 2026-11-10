<?php

namespace App\QueryFilters;

use Illuminate\Database\Eloquent\Builder;

abstract class AbstractQueryFilter implements QueryFilterInterface
{
    public const string SEARCH = 'search';

    public const string LIMIT = 'limit';

    abstract protected function applySearchFilter(Builder $query, $value): Builder;

    public function apply(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters[self::SEARCH] ?? null, function ($query, $value) {
                $this->applySearchFilter($query, $value);
            })
            ->when($filters[self::LIMIT] ?? null, function ($query, $value) {
                $query->limit($value);
            });
    }
}
