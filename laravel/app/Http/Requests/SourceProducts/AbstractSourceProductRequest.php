<?php

namespace App\Http\Requests\SourceProducts;

use App\DTOs\SourceProductDTO;
use App\Http\Requests\RequestInterface;
use App\Models\Brand;
use App\Models\SourceProduct;
use App\Models\SourceProductType;
use App\Models\SourceProductUserData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class AbstractSourceProductRequest extends FormRequest implements RequestInterface
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [

        ];
    }

    public function toDTO(): SourceProductDTO
    {
        return SourceProductDTO::fromArray($this->validated());
    }

    protected function getCommonRules(): array
    {
        return [
            // > relations
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE => [
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_ARRAY,
            ],
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE . '.' . SourceProductType::PARAM_ID => [
                RequestInterface::RULE_INTEGER,
                Rule::exists(SourceProductType::TABLE_NAME, SourceProductType::PARAM_ID),
            ],
            SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA => [
                RequestInterface::RULE_SOMETIMES,
                RequestInterface::RULE_ARRAY,
            ],
            SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA . '.' . SourceProductUserData::PARAM_LISTEN_PRICE_VALUE => [
                RequestInterface::RULE_NULLABLE,
                RequestInterface::RULE_SOMETIMES,
                RequestInterface::RULE_INTEGER,
                RequestInterface::RULE_MAX_DIGITS . ':6',
            ],
            SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA . '.' . SourceProductUserData::PARAM_COMMENT => [
                RequestInterface::RULE_NULLABLE,
                RequestInterface::RULE_SOMETIMES,
                RequestInterface::RULE_STRING,
                RequestInterface::RULE_MAX . ':300',
            ],
            SourceProduct::PARAM_BRAND => [
                RequestInterface::RULE_SOMETIMES,
                RequestInterface::RULE_ARRAY,
            ],
            SourceProduct::PARAM_BRAND . '.' . Brand::PARAM_ID => [
                Rule::requiredIf(
                    fn () => is_array($this->input(SourceProduct::PARAM_BRAND))
                ),
                RequestInterface::RULE_INTEGER,
                Rule::exists(Brand::TABLE_NAME, Brand::PARAM_ID),
            ],
            // < relations
        ];
    }
}
