<?php

namespace App\Http\Requests\PaperTypes;

use App\Models\PaperType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdatePaperTypeRequest extends AbstractPaperTypeRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('paper_type');

        return [
            PaperType::PARAM_LABEL => [
                'required',
                'string',
                'min:2',
                'max:20',
                Rule::unique(PaperType::TABLE_NAME)->ignore($id),
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
