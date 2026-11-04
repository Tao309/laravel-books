<?php

namespace App\Http\Requests\SourceProducts;

use App\DTOs\SourceProductDTO;
use App\Http\Requests\RequestInterface;
use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractSourceProductRequest extends FormRequest implements RequestInterface
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [

        ];
    }

    public function toDTO(): SourceProductDTO
    {
        return SourceProductDTO::fromArray($this->validated());
    }
}
