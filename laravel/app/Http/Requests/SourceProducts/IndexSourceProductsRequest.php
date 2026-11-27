<?php

namespace App\Http\Requests\SourceProducts;

use App\CQRS\Queries\SourceProducts\SourceProductsCollectionQueryDtoInterface;
use App\DTOs\SourceProductsCollectionQueryDto;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\RequestInterface;
use App\Models\Brand;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class IndexSourceProductsRequest extends IndexRequest implements IndexSourceProductsRequestInterface
{
    public const string FIELD_BRAND_CODE = 'brand_code';

    public function messages(): array
    {
        return [
            self::FIELD_BRAND_CODE . '.' . RequestInterface::RULE_STRING => 'BrandCode is not correct.',
        ];
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            self::FIELD_BRAND_CODE => [
                RequestInterface::RULE_NULLABLE,
                RequestInterface::RULE_STRING,
                Rule::exists(Brand::TABLE_NAME, Brand::PARAM_CODE),
            ],
            ...parent::rules(),
        ];
    }

    public function toDTO(): SourceProductsCollectionQueryDtoInterface
    {
        return SourceProductsCollectionQueryDto::fromArray($this->validated());
    }
}
