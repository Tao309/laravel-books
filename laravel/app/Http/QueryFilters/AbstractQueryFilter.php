<?php

namespace App\Http\QueryFilters;

use App\DTOs\CollectionQueryDto;
use Illuminate\Database\Eloquent\Builder;

abstract class AbstractQueryFilter implements QueryFilterInterface
{
    abstract protected function applySearchFilter(Builder $query, $value): Builder;

    public function apply(Builder $query, CollectionQueryDto $dto): Builder
    {
        return $query
            ->when($dto->getSearch(), function ($query, $value) {
                $this->applySearchFilter($query, $value);
            })
            ->when($dto->getLimit(), function ($query, $value) {
                $query->limit($value);
            })
            ->when($dto->getIds(), function ($query, $value) {
                $this->applyIdsFilter($query, $value);
            });
    }

    protected function applyIdsFilter(Builder $query, $value): Builder
    {
        return $query->whereIn($query->getModel()->getKey(), $value);
    }
}
