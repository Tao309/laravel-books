<?php

namespace App\Http\Requests\PublishingBrands;

use App\Http\Requests\RequestInterface;
use App\Models\PublishingBrand;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdatePublishingBrandRequest extends AbstractPublishingBrandRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('publishing_brand');

        return [
            PublishingBrand::PARAM_NAME => [
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_STRING,
                RequestInterface::RULE_MIN . ':2',
                RequestInterface::RULE_MAX . ':255',
                Rule::unique(PublishingBrand::TABLE_NAME)->ignore($id),
            ],
        ];
    }
}
