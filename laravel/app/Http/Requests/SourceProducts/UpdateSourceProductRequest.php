<?php

namespace App\Http\Requests\SourceProducts;

use App\Http\Requests\RequestInterface;
use App\Models\SourceProduct;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateSourceProductRequest extends AbstractSourceProductRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('source_product');

        return [
            SourceProduct::PARAM_TITLE => [
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_STRING,
                RequestInterface::RULE_MIN . ':2',
                RequestInterface::RULE_MAX . ':255',
                Rule::unique(SourceProduct::TABLE_NAME)->ignore($id),
            ],
            ...$this->getCommonRules(),
        ];
    }
}
