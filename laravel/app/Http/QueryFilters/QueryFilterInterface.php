<?php

namespace App\Http\QueryFilters;

use App\DTOs\CollectionQueryDto;
use Illuminate\Database\Eloquent\Builder;

interface QueryFilterInterface
{
    public function apply(Builder $query, CollectionQueryDto $dto): Builder;
}
