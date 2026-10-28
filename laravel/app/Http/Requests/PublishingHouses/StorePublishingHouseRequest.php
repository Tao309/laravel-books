<?php

namespace App\Http\Requests\PublishingHouses;

use App\Models\PublishingHouse;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StorePublishingHouseRequest',
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'АСТ'),
    ],
    type: 'object'
)]
class StorePublishingHouseRequest extends AbstractPublishingHouseRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            PublishingHouse::NAME => [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique('publishing_houses'),
            ],
        ];
    }
}
