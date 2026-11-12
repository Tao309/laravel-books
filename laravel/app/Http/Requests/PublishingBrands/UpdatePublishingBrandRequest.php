<?php

namespace App\Http\Requests\PublishingBrands;

use App\Models\PublishingBrand;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdatePublishingBrandRequest extends AbstractPublishingBrandRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('publishing_brand');

        return [
            PublishingBrand::PARAM_NAME => [
                'required',
                'string',
                'min:5',
                'max:50',
                Rule::unique('publishing_brands')->ignore($id),
            ],
        ];
    }
}
