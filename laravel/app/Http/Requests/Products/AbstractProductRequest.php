<?php

namespace App\Http\Requests\Products;

use App\DTOs\ProductDTO;
use App\Enums\ProductFlag;
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
            Product::PARAM_SHOP_PRODUCT_ID . '.unique' => 'A Product with this ShopProductId and Shop already exists',
            Product::PARAM_SHOP . '.unique' => 'A Product with this ShopProductId and Shop already exists',
            Product::PARAM_BOOK . '.required_without' => 'Book or SourceProduct is required',
            Product::PARAM_SOURCE_PRODUCT . '.required_without' => 'Book or SourceProduct is required',
            Product::PARAM_FLAGS . '.array' => 'Flags is incorrect',
        ];
    }

    public function toDTO(): ProductDTO
    {
        return ProductDTO::fromArray($this->validated());
    }

    protected function getCommonRules(): array
    {
        $flagsRules = [
            Product::PARAM_FLAGS => [
                RequestInterface::RULE_SOMETIMES,
                RequestInterface::RULE_ARRAY,
            ],
        ];

        foreach (ProductFlag::cases() as $flag) {
            $flagsRules[Product::PARAM_FLAGS . ".{$flag->value}"] = [
                RequestInterface::RULE_SOMETIMES,
                RequestInterface::RULE_BOOLEAN,
            ];
        }

        return [
            Product::PARAM_SHOP_PRODUCT_CODE => [
                RequestInterface::RULE_SOMETIMES,
                RequestInterface::RULE_NULLABLE,
                RequestInterface::RULE_STRING,
                RequestInterface::RULE_MIN . ':5',
                RequestInterface::RULE_MAX . ':20',
            ],
            Product::PARAM_TITLE => [
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_STRING,
                RequestInterface::RULE_MIN . ':5',
                RequestInterface::RULE_MAX . ':255',
            ],

            // > flags
            ...$flagsRules,
            // < flags

            // > relations
            Product::PARAM_SHOP . '.' . Shop::PARAM_ID => [
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_INTEGER,
                RequestInterface::RULE_MIN_DIGITS . ':1',
            ],

            Product::PARAM_CITY => [
                RequestInterface::RULE_SOMETIMES,
                RequestInterface::RULE_NULLABLE,
                RequestInterface::RULE_ARRAY,
                ...$this->addRuleIfProductIdEmpty(Rule::exists(City::TABLE_NAME, City::PARAM_ID)),
            ],
            Product::PARAM_CITY . '.' . City::PARAM_ID => [
                Rule::requiredIf(
                    fn () => is_array($this->input(Product::PARAM_CITY))
                ),
                RequestInterface::RULE_INTEGER,
                RequestInterface::RULE_MIN_DIGITS . ':1',
            ],

            Product::PARAM_BOOK => [
                RequestInterface::RULE_SOMETIMES,
                RequestInterface::RULE_NULLABLE,
                RequestInterface::RULE_ARRAY,
                ...$this->addRuleIfProductIdEmpty(Rule::exists(Book::TABLE_NAME, Book::PARAM_ID)),
            ],
            Product::PARAM_BOOK . '.' . Book::PARAM_ID => [
                Rule::requiredIf(
                    fn () => is_array($this->input(Product::PARAM_BOOK))
                ),
                RequestInterface::RULE_INTEGER,
                RequestInterface::RULE_MIN_DIGITS . ':1',
            ],

            Product::PARAM_SOURCE_PRODUCT => [
                RequestInterface::RULE_SOMETIMES,
                RequestInterface::RULE_NULLABLE,
                RequestInterface::RULE_ARRAY,
                ...$this->addRuleIfProductIdEmpty(Rule::exists(SourceProduct::TABLE_NAME, SourceProduct::PARAM_ID)),
            ],
            Product::PARAM_SOURCE_PRODUCT . '.' . SourceProduct::PARAM_ID => [
                Rule::requiredIf(
                    fn () => is_array($this->input(Product::PARAM_SOURCE_PRODUCT))
                ),
                RequestInterface::RULE_INTEGER,
                RequestInterface::RULE_MIN_DIGITS . ':1',
            ],
            // < relations

            // > user_data
            Product::PARAM_PRODUCT_USER_DATA => [
                RequestInterface::RULE_SOMETIMES,
                RequestInterface::RULE_ARRAY,
            ],
            Product::PARAM_PRODUCT_USER_DATA . '.' . ProductUserData::PARAM_AVAILABLE => [
                RequestInterface::RULE_SOMETIMES,
                RequestInterface::RULE_BOOLEAN,
            ],
            Product::PARAM_PRODUCT_USER_DATA . '.' . ProductUserData::PARAM_NOT_AVAILABLE_DATE_FROM => [
                RequestInterface::RULE_SOMETIMES,
                RequestInterface::RULE_NULLABLE,
                RequestInterface::RULE_DATE_FORMAT . ':Y.m.d,Y-m-d\TH:i:s.v\Z',
            ],
            Product::PARAM_PRODUCT_USER_DATA . '.' . ProductUserData::PARAM_AVAILABLE_DATE_FROM => [
                RequestInterface::RULE_SOMETIMES,
                RequestInterface::RULE_NULLABLE,
                RequestInterface::RULE_DATE_FORMAT . ':Y.m.d,Y-m-d\TH:i:s.v\Z',
            ],
            Product::PARAM_PRODUCT_USER_DATA . '.' . ProductUserData::PARAM_LISTEN_PRICE_VALUE => [
                RequestInterface::RULE_SOMETIMES,
                RequestInterface::RULE_NULLABLE,
                RequestInterface::RULE_INTEGER,
            ],
            Product::PARAM_PRODUCT_USER_DATA . '.' . ProductUserData::PARAM_LISTEN_QTY_VALUE => [
                RequestInterface::RULE_SOMETIMES,
                RequestInterface::RULE_NULLABLE,
                'integer',
            ],
            Product::PARAM_PRODUCT_USER_DATA . '.' . ProductUserData::PARAM_RELEASE_DATE => [
                RequestInterface::RULE_SOMETIMES,
                RequestInterface::RULE_NULLABLE,
                RequestInterface::RULE_DATE_FORMAT . ':Y.m.d,Y-m-d\TH:i:s.v\Z',
            ],
            Product::PARAM_PRODUCT_USER_DATA . '.' . ProductUserData::PARAM_IS_ARCHIVE => [
                RequestInterface::RULE_SOMETIMES,
                RequestInterface::RULE_BOOLEAN,
            ],
            Product::PARAM_PRODUCT_USER_DATA . '.' . ProductUserData::PARAM_COMMENT => [
                RequestInterface::RULE_SOMETIMES,
                RequestInterface::RULE_NULLABLE,
                RequestInterface::RULE_STRING,
                RequestInterface::RULE_MAX . ':300',
            ],
            // < user_data

            // > prices
            Product::PARAM_PRICES => [
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_ARRAY,
            ],
            Product::PARAM_PRICES . '.*.' . ProductPrice::PARAM_PRICE => [
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_INTEGER,
            ],
            Product::PARAM_PRICES . '.*.' . ProductPrice::PARAM_DATE => [
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_DATE_FORMAT . ':Y.m.d,Y-m-d\TH:i:s.v\Z',
            ],
            // < prices

            // > stocks
            Product::PARAM_STOCKS => [
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_ARRAY,
            ],
            Product::PARAM_STOCKS . '.*.' . ProductStock::PARAM_QTY => [
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_INTEGER,
            ],
            Product::PARAM_STOCKS . '.*.' . ProductStock::PARAM_DATE => [
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_DATE_FORMAT . ':Y.m.d,Y-m-d\TH:i:s.v\Z',
            ],
            Product::PARAM_STOCKS . '.*.' . ProductStock::PARAM_LOG => [
                RequestInterface::RULE_SOMETIMES,
                RequestInterface::RULE_NULLABLE,
                RequestInterface::RULE_ARRAY,
            ],
            // < stocks
        ];
    }
}
