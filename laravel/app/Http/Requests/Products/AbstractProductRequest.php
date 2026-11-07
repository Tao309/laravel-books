<?php

namespace App\Http\Requests\Products;

use App\DTOs\ProductDTO;
use App\Http\Requests\RequestInterface;
use App\Models\Book;
use App\Models\City;
use App\Models\Product;
use App\Models\ProductUserData;
use App\Models\Shop;
use App\Models\SourceProduct;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class AbstractProductRequest extends FormRequest implements RequestInterface
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            Product::PARAM_SHOP_PRODUCT_ID.'.unique' => 'A Book with this ShopProductId and Shop already exists',
            Product::PARAM_SHOP.'.unique' => 'A Book with this ShopProductId and Shop already exists',
            Product::PARAM_BOOK.'.required_without' => 'Book or SourceProduct is required',
            Product::PARAM_SOURCE_PRODUCT.'.required_without' => 'Book or SourceProduct is required',
        ];
    }

    public function toDTO(): ProductDTO
    {
        return ProductDTO::fromArray($this->validated());
    }

    protected function getCommonRules(): array
    {
        return [
            Product::PARAM_SHOP_PRODUCT_CODE => [
                'sometimes',
                'nullable',
                'string',
                'min:5',
                'max:20',
            ],
            Product::PARAM_TITLE => [
                'required',
                'string',
                'min:5',
                'max:255',
            ],
            // > relations
            Product::PARAM_SHOP.'.'.Shop::PARAM_ID => [
                'required',
                'integer',
                'min_digits:1',
            ],
            Product::PARAM_CITY => [
                'sometimes',
                'nullable',
                Rule::exists(City::TABLE_NAME, City::PARAM_ID),
            ],
            Product::PARAM_CITY.'.'.City::PARAM_ID => [
                'sometimes',
                'nullable',
                'integer',
                'min_digits:1',
            ],
            Product::PARAM_BOOK => [
                'sometimes',
                'nullable',
                Rule::exists(Book::TABLE_NAME, Book::PARAM_ID),
            ],
            Product::PARAM_BOOK.'.'.Book::PARAM_ID => [
                'sometimes',
                'nullable',
                'integer',
                'min_digits:1',
            ],
            Product::PARAM_SOURCE_PRODUCT => [
                'sometimes',
                'nullable',
                Rule::exists(SourceProduct::TABLE_NAME, SourceProduct::PARAM_ID),
            ],
            Product::PARAM_SOURCE_PRODUCT.'.'.SourceProduct::PARAM_ID => [
                'sometimes',
                'nullable',
                'integer',
                'min_digits:1',
            ],
            // < relations

            // > user_data
            Product::PARAM_PRODUCT_USER_DATA => [
                'sometimes',
                'array',
            ],
            Product::PARAM_PRODUCT_USER_DATA.'.'.ProductUserData::PARAM_AVAILABLE => [
                'sometimes',
                'boolean',
            ],
            Product::PARAM_PRODUCT_USER_DATA.'.'.ProductUserData::PARAM_NOT_AVAILABLE_DATE_FROM => [
                'sometimes',
                'date_format:Y.m.d',
            ],
            Product::PARAM_PRODUCT_USER_DATA.'.'.ProductUserData::PARAM_AVAILABLE_DATE_FROM => [
                'sometimes',
                'date_format:Y.m.d',
            ],
            Product::PARAM_PRODUCT_USER_DATA.'.'.ProductUserData::PARAM_LISTEN_PRICE_VALUE => [
                'sometimes',
                'integer',
            ],
            Product::PARAM_PRODUCT_USER_DATA.'.'.ProductUserData::PARAM_LISTEN_QTY_VALUE => [
                'sometimes',
                'integer',
            ],
            Product::PARAM_PRODUCT_USER_DATA.'.'.ProductUserData::PARAM_RELEASE_DATE => [
                'sometimes',
                'date_format:Y.m.d',
            ],
            Product::PARAM_PRODUCT_USER_DATA.'.'.ProductUserData::PARAM_IS_ARCHIVE => [
                'sometimes',
                'boolean',
            ],
            Product::PARAM_PRODUCT_USER_DATA.'.'.ProductUserData::PARAM_COMMENT => [
                'sometimes',
                'string',
                'max:300',
            ],
            // < user_data
        ];
    }
}
