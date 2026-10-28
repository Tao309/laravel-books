<?php

namespace App\Http\Requests\PublishingHouses;

use App\DTOs\PublishingHouseDTO;
use App\Models\PublishingHouse;
use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractPublishingHouseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
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
