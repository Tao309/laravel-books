<?php

namespace App\Http\Requests\Cities;

use App\DTOs\CityDTO;
use App\Http\Requests\RequestInterface;
use App\Models\City;
use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractCityRequest extends FormRequest implements RequestInterface
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
