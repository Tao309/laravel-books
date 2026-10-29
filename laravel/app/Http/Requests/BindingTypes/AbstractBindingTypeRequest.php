<?php

namespace App\Http\Requests\BindingTypes;

use App\DTOs\BindingTyperDTO;
use App\Http\Requests\RequestInterface;
use App\Models\BindingType;
use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractBindingTypeRequest extends FormRequest implements RequestInterface
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            BindingType::LABEL.'.unique' => 'An Binding Type with this label already exists',
        ];
    }

    public function toDTO(): BindingTyperDTO
    {
        return BindingTyperDTO::fromArray($this->validated());
    }
}
