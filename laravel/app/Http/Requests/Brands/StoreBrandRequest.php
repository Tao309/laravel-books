<?php

namespace App\Http\Requests\Brands;

use App\Http\Requests\RequestInterface;
use App\Models\Brand;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class StoreBrandRequest extends AbstractBrandRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            Brand::PARAM_CODE => [
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_STRING,
                RequestInterface::RULE_MIN . ':2',
                RequestInterface::RULE_MAX . ':100',
                Rule::unique(Brand::TABLE_NAME),
            ],
            ...$this->getCommonRules(),
        ];
    }
}
