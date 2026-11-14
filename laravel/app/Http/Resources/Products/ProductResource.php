<?php

namespace App\Http\Resources\Products;

use App\Http\Resources\AbstractResource;
use App\Http\Resources\Books\BookResource;
use App\Http\Resources\Cities\CityResource;
use App\Http\Resources\ProductPrices\ProductPriceResource;
use App\Http\Resources\ProductStocks\ProductStockResource;
use App\Http\Resources\ProductUserDatas\ProductUserDataResource;
use App\Http\Resources\Shops\ShopResource;
use App\Http\Resources\SourceProducts\SourceProductResource;
use App\Models\Book;
use App\Models\Product;
use App\Models\SourceProduct;
use App\Services\DateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ProductResource extends AbstractResource
{
    public function getArrayData(Request $request): array
    {
        return [
            Product::PARAM_ID => $this->offsetGet(Product::PARAM_ID),
            Product::PARAM_SHOP_PRODUCT_ID => $this->offsetGet(Product::PARAM_SHOP_PRODUCT_ID),
            Product::PARAM_SHOP_PRODUCT_CODE => $this->offsetGet(Product::PARAM_SHOP_PRODUCT_CODE),
            Product::PARAM_TITLE => $this->offsetGet(Product::PARAM_TITLE),

            Model::UPDATED_AT => DateService::createFromDate($this->updated_at),
            Model::CREATED_AT => DateService::createFromDate($this->created_at),

            Product::PARAM_MIN_PRICE => $this->minPrice(),
            Product::PARAM_LAST_QTY => $this->lastQty(),
            Product::PARAM_LAST_STOCK => ProductStockResource::make($this->lastStock()),

            // > relations
            Product::PARAM_SHOP => $this->whenLoaded(
                Product::PARAM_SHOP,
                fn () => ShopResource::make($this->shop)
            ),
            Product::PARAM_CITY => $this->whenLoaded(
                Product::PARAM_CITY,
                fn () => CityResource::make($this->city)
            ),
            Product::PARAM_BOOK => $this->whenLoaded(
                Product::PARAM_SOURCE,
                fn () => $this->source instanceof Book ? BookResource::make($this->source) : null
            ),
            Product::PARAM_SOURCE_PRODUCT => $this->whenLoaded(
                Product::PARAM_SOURCE,
                fn () => $this->source instanceof SourceProduct ? SourceProductResource::make($this->source) : null
            ),
            Product::PARAM_PRODUCT_USER_DATA => $this->whenLoaded(
                'productUserData',
                fn () => ProductUserDataResource::make($this->productUserData)
            ),
            Product::PARAM_PRICES => $this->whenLoaded(
                Product::PARAM_PRICES,
                fn () => ProductPriceResource::collection($this->prices)
            ),
            Product::PARAM_STOCKS => $this->whenLoaded(
                Product::PARAM_STOCKS,
                fn () => ProductStockResource::collection($this->stocks)
            ),
            // < relations
        ];
    }
}
