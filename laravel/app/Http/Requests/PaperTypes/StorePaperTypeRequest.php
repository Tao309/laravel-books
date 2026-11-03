<?php

namespace App\Http\Requests\PaperTypes;

use App\Models\PaperType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StorePaperTypeRequest',
    properties: [
        new OA\Property(property: 'label', type: 'string', example: 'Типографская'),
        new OA\Property(
            property: 'description',
            type: 'string',
            example: 'Бумага средней плотности, светло-серая, слегка шероховатая'
        ),
    ],
    type: 'object'
)]
class StorePaperTypeRequest extends AbstractPaperTypeRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            PaperType::PARAM_LABEL => [
                'required',
                'string',
                'min:2',
                'max:20',
                Rule::unique(PaperType::TABLE_NAME),
            ],
            PaperType::PARAM_DESCRIPTION => [
                'sometimes',
                'nullable',
                'string',
                'min:2',
                'max:50',
            ],
        ];
    }
}
