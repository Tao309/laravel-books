<?php

namespace App\Http\Requests\Cities;

use App\DTOs\CityDTO;
use App\Models\City;
use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractCityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            City::CODE.'.unique' => 'A City with this code already exists',
            City::TITLE.'.unique' => 'A City with this title already exists',
        ];
    }

    public function toDTO(): CityDTO
    {
        return CityDTO::fromArray($this->validated());
    }
}
