<?php

namespace App\QueryFilters;

use App\Models\Author;
use App\Services\AuthorService;
use Illuminate\Database\Eloquent\Builder;

class AuthorQueryFilter extends AbstractQueryFilter
{
    public function __construct(
        private readonly AuthorService $authorService,
    ) {}

    public function applySearchFilter(Builder $query, $value): Builder
    {
        $firstNameField = Author::PARAM_FIRST_NAME;
        $lastNameField = Author::PARAM_LAST_NAME;
        [$firstName, $lastName] = $this->authorService->splitAuthorFullName(mb_strtolower($value));

        $query->whereRaw("LOWER({$firstNameField}) LIKE ?", "%{$firstName}%");

        if ($lastName) {
            $query->whereRaw("LOWER({$lastNameField}) LIKE ?", "%{$lastName}%");
        } else {
            $query->orWhereRaw("LOWER({$lastNameField}) LIKE ?", "%{$firstName}%");
        }

        $query->limit(7);

        return $query;
    }
}
