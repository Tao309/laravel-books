<?php

namespace App\Http\Requests\SourceProducts;

use App\Models\SourceProduct;
use App\Models\SourceProductType;
use App\Models\SourceProductUserData;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class StoreSourceProductRequest extends AbstractSourceProductRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            SourceProduct::PARAM_TITLE => [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique(SourceProduct::TABLE_NAME),
            ],
            // > relations
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE => [
                'required',
                Rule::exists(SourceProductType::TABLE_NAME, SourceProductType::PARAM_ID),
            ],
            SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA => [
                'sometimes',
                'array',
            ],
            SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA.'.'.SourceProductUserData::PARAM_LISTEN_PRICE_VALUE => [
                'sometimes',
                'integer',
                'max_digits:9',
            ],
            SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA.'.'.SourceProductUserData::PARAM_COMMENT => [
                'sometimes',
                'string',
                'max:300',
            ],
            // < relations
        ];
    }
}
