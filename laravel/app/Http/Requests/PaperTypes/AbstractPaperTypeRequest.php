<?php

namespace App\Http\Requests\PaperTypes;

use App\DTOs\PaperTypeDTO;
use App\Http\Requests\RequestInterface;
use App\Models\PaperType;
use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractPaperTypeRequest extends FormRequest implements RequestInterface
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            PaperType::PARAM_LABEL.'.unique' => 'A PaperType with this label already exists',
        ];
    }

    public function toDTO(): PaperTypeDTO
    {
        return PaperTypeDTO::fromArray($this->validated());
    }
}
