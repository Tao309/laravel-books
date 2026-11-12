<?php

namespace App\Http\Requests\SourceProducts;

use App\Models\SourceProduct;
use App\Models\SourceProductType;
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
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique(SourceProduct::TABLE_NAME)->ignore($id),
            ],
            // > relations
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE => [
                'required',
                Rule::exists(SourceProductType::TABLE_NAME, SourceProductType::PARAM_ID),
            ],
            // < relations
        ];
    }
}
