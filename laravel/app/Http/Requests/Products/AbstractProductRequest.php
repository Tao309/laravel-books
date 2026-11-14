<?php

namespace App\Http\Requests\Products;

use App\DTOs\ProductDTO;
use App\Http\Requests\RequestInterface;
use App\Models\Book;
use App\Models\City;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductStock;
use App\Models\ProductUserData;
use App\Models\Shop;
use App\Models\SourceProduct;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class AbstractProductRequest extends FormRequest implements RequestInterface
{
    protected ?int $productId = null {
        get {
            return $this->productId;
        }
        set {
            $this->productId = $value;
        }
    }

    protected function addRuleIfProductIdEmpty($rule): array
    {
        return ! $this->productId ? [$rule] : [];
    }

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
                ...$this->addRuleIfProductIdEmpty(Rule::exists(City::TABLE_NAME, City::PARAM_ID)),
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
                ...$this->addRuleIfProductIdEmpty(Rule::exists(Book::TABLE_NAME, Book::PARAM_ID)),
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
                ...$this->addRuleIfProductIdEmpty(Rule::exists(SourceProduct::TABLE_NAME, SourceProduct::PARAM_ID)),
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
                'nullable',
                'date_format:Y.m.d',
            ],
            Product::PARAM_PRODUCT_USER_DATA.'.'.ProductUserData::PARAM_AVAILABLE_DATE_FROM => [
                'sometimes',
                'nullable',
                'date_format:Y.m.d',
            ],
            Product::PARAM_PRODUCT_USER_DATA.'.'.ProductUserData::PARAM_LISTEN_PRICE_VALUE => [
                'sometimes',
                'nullable',
                'integer',
            ],
            Product::PARAM_PRODUCT_USER_DATA.'.'.ProductUserData::PARAM_LISTEN_QTY_VALUE => [
                'sometimes',
                'nullable',
                'integer',
            ],
            Product::PARAM_PRODUCT_USER_DATA.'.'.ProductUserData::PARAM_RELEASE_DATE => [
                'sometimes',
                'nullable',
                'date_format:Y.m.d',
            ],
            Product::PARAM_PRODUCT_USER_DATA.'.'.ProductUserData::PARAM_IS_ARCHIVE => [
                'sometimes',
                'boolean',
            ],
            Product::PARAM_PRODUCT_USER_DATA.'.'.ProductUserData::PARAM_COMMENT => [
                'sometimes',
                'nullable',
                'string',
                'max:300',
            ],
            // < user_data
            // > prices
            Product::PARAM_PRICES => [
                'sometimes',
                'array',
            ],
            Product::PARAM_PRICES.'.*.'.ProductPrice::PARAM_PRICE => [
                'required',
                'integer',
            ],
            Product::PARAM_PRICES.'.*.'.ProductPrice::PARAM_DATE => [
                'required',
                'date_format:Y-m-d H:i:s',
            ],
            // < prices
            // > stocks
            Product::PARAM_STOCKS => [
                'sometimes',
                'array',
            ],
            Product::PARAM_STOCKS.'.*.'.ProductStock::PARAM_QTY => [
                'required',
                'integer',
            ],
            Product::PARAM_STOCKS.'.*.'.ProductStock::PARAM_DATE => [
                'required',
                'date_format:Y-m-d H:i:s',
            ],
            Product::PARAM_STOCKS.'.*.'.ProductStock::PARAM_LOG => [
                'sometimes',
                'nullable',
                'string',
            ],
            // < stocks
        ];
    }
}
