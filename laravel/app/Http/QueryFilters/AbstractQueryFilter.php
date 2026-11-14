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

    protected function applyTitleSearchFilter(
        Builder $query,
        string $titleField,
        string $value,
        array $toRemove = [],
    ): Builder {
        $value = mb_strtolower($value);

        if ($toRemove !== []) {
            $value = str_ireplace($toRemove, '', $value);
        }

        $value = preg_replace('/\s+/u', ' ', $value);
        $value = trim($value);

        if ($value === '') {
            return $query->whereRaw('1 = 0');
        }

        $searchValues = [$value];

        foreach ([':', '.'] as $separator) {
            if (str_contains($value, $separator)) {
                $searchValues[] = trim(explode($separator, $value, 2)[0]);
            }
        }

        if (str_contains($value, ' ')) {
            $searchValues[] = implode(
                ' ',
                array_slice(explode(' ', $value), 0, 2)
            );
        }

        $searchValues = array_unique(
            array_filter($searchValues, fn ($item) => $item !== '')
        );

        $query->where(function (Builder $query) use ($titleField, $searchValues) {
            foreach ($searchValues as $searchValue) {
                $query->orWhereRaw(
                    "LOWER({$titleField}) LIKE ?",
                    ["%{$searchValue}%"]
                );
            }
        });

        $query->orderByRaw(
            "CASE
            WHEN LOWER({$titleField}) = ? THEN 1
            WHEN LOWER({$titleField}) LIKE ? THEN 2
            WHEN LOWER({$titleField}) LIKE ? THEN 3
            WHEN LOWER({$titleField}) LIKE ? THEN 4
            ELSE 5
        END ASC",
            [
                $value,
                "{$value}%",
                "%{$value}",
                "%{$value}%",
            ]
        );

        return $query->limit(7);
    }
}
