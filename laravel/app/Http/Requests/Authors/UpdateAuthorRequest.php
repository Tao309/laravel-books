<?php

namespace App\Http\Requests\Authors;

use App\Models\Author;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateAuthorRequest',
    properties: [
        new OA\Property(property: 'first_name', type: 'string', example: 'Фёдор'),
        new OA\Property(property: 'last_name', type: 'string', example: 'Достоевский'),
    ],
    type: 'object'
)]
class UpdateAuthorRequest extends AbstractAuthorRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('author');

        return [
            Author::FIRST_NAME => [
                'string',
                'min:2',
                'max:100',
                Rule::unique('authors')
                    ->where(function (Builder $query) {
                        return $query->where('first_name', $this->first_name)->where('last_name', $this->last_name);
                    })
                    ->ignore($id),
            ],
            Author::LAST_NAME => ['string', 'min:2', 'max:100'],
        ];
    }
}
