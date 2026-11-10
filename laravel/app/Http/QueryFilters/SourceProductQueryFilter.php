<?php

namespace App\Http\QueryFilters;

use App\Models\SourceProduct;
use Illuminate\Database\Eloquent\Builder;

class SourceProductQueryFilter extends AbstractQueryFilter
{
    public function applySearchFilter(Builder $query, $value): Builder
    {
        $titleField = SourceProduct::PARAM_TITLE;
        $value = mb_strtolower($value);

        $query
            ->select('*')
            ->whereRaw("LOWER({$titleField}) = ?", $value)
            ->orWhereRaw("LOWER({$titleField}) LIKE ?", "%{$value}%");

        $query
            ->selectRaw(implode(' ', [
                '(CASE',
                "WHEN {$titleField} = '{$value}' THEN 1",
                "WHEN {$titleField} LIKE '{$value}%' THEN 2",
                "WHEN {$titleField} LIKE '%{$value}%' THEN 3",
                "WHEN {$titleField} LIKE '%{$value}'  THEN 4",
                'ELSE 5',
                'END) AS sort_title',
            ]))
            ->orderBy('sort_title', 'ASC');

        if (str_contains($value, ':')) {
            $explodeTitle = explode(':', $value);
            $splitTitle = reset($explodeTitle);

            $query->orWhereRaw("LOWER({$titleField}) LIKE ?", "%{$splitTitle}%");
        }

        if (str_contains($value, '.')) {
            $explodeTitle = explode('.', $value);
            $splitTitle = reset($explodeTitle);

            $query->orWhereRaw("LOWER({$titleField}) LIKE ?", "%{$splitTitle}%");
        }

        if (str_contains($value, ' ')) {
            $explodeTitle = explode(' ', $value);
            $splitTitles = \array_slice($explodeTitle, 0, 2);
            $splitTitlesJoin = implode(' ', $splitTitles);

            $query->orWhereRaw("LOWER({$titleField}) LIKE ?", "%{$splitTitlesJoin}%");
        }

        $query->limit(7);

        return $query;
    }
}
