<?php

namespace App\Http\Requests\PaperTypes;

use App\Http\Requests\RequestInterface;
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
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_STRING,
                RequestInterface::RULE_MIN . ':2',
                RequestInterface::RULE_MAX . ':20',
                Rule::unique(PaperType::TABLE_NAME)->ignore($id),
            ],
            ...$this->getCommonRules(),
        ];
    }
}
