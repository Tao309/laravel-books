<?php

namespace App\Http\Requests\PaperTypes;

use App\Models\PaperType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

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
