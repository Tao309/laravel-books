<?php

namespace App\Http\Requests\Authors;

use App\Http\Requests\RequestInterface;
use App\Models\Author;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;

class StoreAuthorRequest extends AbstractAuthorRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            Author::PARAM_FIRST_NAME => [
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_STRING,
                RequestInterface::RULE_MIN . ':2',
                RequestInterface::RULE_MAX . ':100',
                Rule::unique(Author::TABLE_NAME)
                    ->where(function (Builder $query) {
                        return $query->where(Author::PARAM_FIRST_NAME, $this->first_name)
                            ->where(Author::PARAM_LAST_NAME, $this->last_name);
                    }),
            ],
            ...$this->getCommonRules(),
        ];
    }
}
