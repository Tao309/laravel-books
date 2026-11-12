<?php

namespace App\Http\Requests\PublishingHouses;

use App\Models\PublishingHouse;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class StorePublishingHouseRequest extends AbstractPublishingHouseRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            PublishingHouse::PARAM_NAME => [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique('publishing_houses'),
            ],
        ];
    }
}
