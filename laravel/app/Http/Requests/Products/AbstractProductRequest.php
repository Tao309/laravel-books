<?php

namespace App\Http\Requests\Products;

use App\DTOs\ProductDTO;
use App\Http\Requests\RequestInterface;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

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
}
