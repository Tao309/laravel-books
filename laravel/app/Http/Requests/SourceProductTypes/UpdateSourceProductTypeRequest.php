<?php

namespace App\Http\Requests\SourceProductTypes;

use App\Http\Requests\RequestInterface;
use App\Models\SourceProductType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateSourceProductTypeRequest extends AbstractSourceProductTypeRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('source_product_type');

        return [
            SourceProductType::PARAM_CODE => [
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_STRING,
                RequestInterface::RULE_MIN . ':2',
                RequestInterface::RULE_MAX . ':50',
                Rule::unique(SourceProductType::TABLE_NAME)->ignore($id),
            ],
            SourceProductType::PARAM_NAME => [
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_STRING,
                RequestInterface::RULE_MIN . ':2',
                RequestInterface::RULE_MAX . ':50',
                Rule::unique(SourceProductType::TABLE_NAME)->ignore($id),
            ],
        ];
    }
}
