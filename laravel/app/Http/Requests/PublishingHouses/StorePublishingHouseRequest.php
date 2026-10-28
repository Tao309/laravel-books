<?php

namespace App\Http\Requests\PublishingHouses;

use App\DTOs\PublishingHouseDTO;
use App\Models\PublishingHouse;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StorePublishingHouseRequest',
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'АСТ'),
    ],
    type: 'object'
)]
class StorePublishingHouseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

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

    public function messages(): array
    {
        return [
            PublishingHouse::NAME.'.unique' => 'A PublishingHouse with this name already exists',
        ];
    }

    public function toDTO(): PublishingHouseDTO
    {
        return PublishingHouseDTO::fromArray($this->validated());
    }
}
