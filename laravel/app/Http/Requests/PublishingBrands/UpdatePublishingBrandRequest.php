<?php

namespace App\Http\Requests\PublishingBrands;

use App\Models\PublishingBrand;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdatePublishingBrandRequest',
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'Астрель СПб'),
    ],
    type: 'object'
)]
class UpdatePublishingBrandRequest extends AbstractPublishingBrandRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('publishing_brand');

        return [
            PublishingBrand::NAME => [
                'required',
                'string',
                'min:5',
                'max:50',
                Rule::unique('publishing_brands')->ignore($id),
            ],
        ];
    }
}
