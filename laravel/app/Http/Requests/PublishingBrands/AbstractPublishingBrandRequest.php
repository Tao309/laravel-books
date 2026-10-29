<?php

namespace App\Http\Requests\PublishingBrands;

use App\DTOs\PublishingBrandDTO;
use App\Http\Requests\RequestInterface;
use App\Models\PublishingBrand;
use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractPublishingBrandRequest extends FormRequest implements RequestInterface
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            PublishingBrand::NAME.'.unique' => 'A PublishingBrand with this name already exists',
        ];
    }

    public function toDTO(): PublishingBrandDTO
    {
        return PublishingBrandDTO::fromArray($this->validated());
    }
}
