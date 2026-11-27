<?php

namespace App\Http\QueryFilters;

use App\Models\Author;
use App\Services\AuthorService;
use Illuminate\Database\Eloquent\Builder;

class AuthorQueryFilter extends AbstractQueryFilter
{
    public function __construct(
        private readonly AuthorService $authorService,
    ) {}

    protected function applySearchFilter(Builder $query, $value): Builder
    {
        $firstNameField = Author::PARAM_FIRST_NAME;
        $lastNameField = Author::PARAM_LAST_NAME;
        [$firstName, $lastName] = $this->authorService->splitAuthorFullName(mb_strtolower($value));

        $query->where(function (Builder $query) use (
            $firstNameField,
            $lastNameField,
            $firstName,
            $lastName
        ) {
            $query->whereRaw(
                "LOWER({$firstNameField}) LIKE ?",
                ['%' . mb_strtolower($firstName) . '%']
            );

            if ($lastName) {
                $query->whereRaw(
                    "LOWER({$lastNameField}) LIKE ?",
                    ['%' . mb_strtolower($lastName) . '%']
                );
            } else {
                $query->orWhereRaw(
                    "LOWER({$lastNameField}) LIKE ?",
                    ['%' . mb_strtolower($firstName) . '%']
                );
            }
        })
            ->orderByRaw(
                "CASE
                    WHEN LOWER({$firstNameField}) = ? THEN 0
                    WHEN LOWER({$lastNameField}) = ? THEN 0
                    ELSE 1
                END",
                [
                    mb_strtolower($firstName),
                    mb_strtolower($lastName ?: $firstName),
                ]
            )
            ->limit(7);

        return $query;
    }
}
