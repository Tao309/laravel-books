<?php

namespace App\Http\Requests\Brands;

use App\DTOs\BrandDTO;
use App\Http\Requests\RequestInterface;
use App\Models\Brand;
use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractBrandRequest extends FormRequest implements RequestInterface
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            Brand::PARAM_CODE . '.unique' => 'A Brand with this name already exists',
        ];
    }

    public function toDTO(): BrandDTO
    {
        return BrandDTO::fromArray($this->validated());
    }

    protected function getCommonRules(): array
    {
        return [];
    }
}
