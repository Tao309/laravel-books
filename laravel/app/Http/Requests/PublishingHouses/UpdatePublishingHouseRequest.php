<?php

namespace App\Http\Requests\PublishingHouses;

use App\Models\PublishingHouse;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdatePublishingHouseRequest',
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'АСТ'),
    ],
    type: 'object'
)]
class UpdatePublishingHouseRequest extends FormRequest
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
        $id = $this->route('publishing_house');

        return [
            PublishingHouse::NAME => [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique('publishing_houses')->ignore($id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            PublishingHouse::NAME.'.unique' => 'A PublishingHouse with this name already exists',
        ];
    }
}
