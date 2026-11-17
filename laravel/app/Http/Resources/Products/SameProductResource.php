<?php

namespace App\Http\Resources\Products;

use App\Http\Resources\AbstractResource;
use App\Http\Resources\ProductUserDatas\ProductUserDataResource;
use App\Http\Resources\Shops\ShopResource;
use App\Models\Product;
use Illuminate\Http\Request;

class SameProductResource extends AbstractResource
{
    public function getArrayData(Request $request): array
    {
        return [
            Product::PARAM_ID => $this->offsetGet(Product::PARAM_ID),
            Product::PARAM_SHOP_PRODUCT_ID => $this->offsetGet(Product::PARAM_SHOP_PRODUCT_ID),
            Product::PARAM_SHOP_PRODUCT_CODE => $this->offsetGet(Product::PARAM_SHOP_PRODUCT_CODE),
            Product::PARAM_TITLE => $this->offsetGet(Product::PARAM_TITLE),
            Product::PARAM_MIN_PRICE => $this->resource->getAttribute(Product::PARAM_MIN_PRICE),

            // > relations
            Product::PARAM_PRODUCT_USER_DATA => $this->whenLoaded(
                'productUserData',
                fn () => ProductUserDataResource::make($this->productUserData)
            ),
            Product::PARAM_SHOP => $this->whenLoaded(
                Product::PARAM_SHOP,
                fn () => ShopResource::make($this->shop)
            ),
            // < relations
        ];
    }
}
