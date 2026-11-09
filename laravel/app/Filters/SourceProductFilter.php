<?php

namespace App\Filters;

use App\Models\SourceProduct;
use Illuminate\Database\Eloquent\Builder;

class SourceProductFilter implements FilterInterface
{
    public const string SEARCH = 'search';

    public const string LIMIT = 'limit';

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

        return $query;
    }
}
