<?php

namespace App\Http\QueryFilters;

use App\Models\Book;
use Illuminate\Database\Eloquent\Builder;

class BookQueryFilter extends AbstractQueryFilter
{
    public function applySearchFilter(Builder $query, $value): Builder
    {
        $toRemove = [
            'подвижная фигурка',
            'фигурка подвижная',
            'фигурка',
            'joytoy',
            'joy-toy',
            'joy toy',
            'w40k',
            'w30k',
            '40k',
            '30k',
            '40000',
            'warhammer 40k',
            'warhammer',
            'the horus heresy',
            'horus heresy',
            'подарочная модель',
            '1/18',
            '1/16',
            '1/14',
            '1/20',
            'action figures',
        ];

        return $this->applyTitleSearchFilter(
            $query,
            Book::PARAM_TITLE,
            $value,
            $toRemove
        );
    }
}
