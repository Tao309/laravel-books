<?php

namespace App\Http\Requests\SourceProducts;

use App\Http\Requests\RequestInterface;
use App\Models\Brand;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SourceProductsByBrandCodeRequest extends FormRequest
{
    public const string FIELD_BRAND_CODE = 'brand_code';

    public function rules(): array
    {
        return [
            self::FIELD_BRAND_CODE => [
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_STRING,
                Rule::exists(Brand::TABLE_NAME, Brand::PARAM_CODE),
            ],
        ];
    }

    public function validationData(): array
    {
        $data = parent::validationData();

        if ($this->route(self::FIELD_BRAND_CODE)) {
            $data = array_merge(
                $data,
                [self::FIELD_BRAND_CODE => $this->route(self::FIELD_BRAND_CODE)]
            );
        }

        return $data;
    }
}
