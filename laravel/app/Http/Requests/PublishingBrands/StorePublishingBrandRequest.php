<?php

namespace App\Http\Requests\PublishingBrands;

use App\Models\PublishingBrand;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StorePublishingBrandRequest',
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'Астрель СПб'),
    ],
    type: 'object'
)]
class StorePublishingBrandRequest extends AbstractPublishingBrandRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            PublishingBrand::NAME => [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique('publishing_brands'),
            ],
        ];
    }
}
