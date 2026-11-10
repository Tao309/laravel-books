<?php

namespace App\QueryFilters;

use Illuminate\Database\Eloquent\Builder;

interface QueryFilterInterface
{
    public function apply(Builder $query, array $filters): Builder;
}
