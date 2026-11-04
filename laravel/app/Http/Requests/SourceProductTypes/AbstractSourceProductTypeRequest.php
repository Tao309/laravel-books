<?php

namespace App\Http\Requests\SourceProductTypes;

use App\DTOs\SourceProductTypeDTO;
use App\Http\Requests\RequestInterface;
use App\Models\SourceProductType;
use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractSourceProductTypeRequest extends FormRequest implements RequestInterface
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            SourceProductType::PARAM_CODE.'.unique' => 'A SourceProductType with this code already exists',
            SourceProductType::PARAM_NAME.'.unique' => 'A SourceProductType with this name already exists',
        ];
    }

    public function toDTO(): SourceProductTypeDTO
    {
        return SourceProductTypeDTO::fromArray($this->validated());
    }
}
