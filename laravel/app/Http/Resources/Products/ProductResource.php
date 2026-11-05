<?php

namespace App\Http\Resources\Products;

use App\Http\Resources\AbstractResource;
use App\Http\Resources\Books\BookResource;
use App\Http\Resources\Cities\CityResource;
use App\Http\Resources\Shops\ShopResource;
use App\Http\Resources\SourceProducts\SourceProductResource;
use App\Models\Book;
use App\Models\Product;
use App\Models\SourceProduct;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductResource',
    properties: [
        new OA\Property(property: Product::PARAM_ID, type: 'integer', example: 1),
        new OA\Property(property: Product::PARAM_SHOP_PRODUCT_ID, type: 'integer', example: '3315320967'),
        new OA\Property(property: Product::PARAM_SHOP_PRODUCT_CODE, type: 'integer', example: '1903147507'),
        new OA\Property(property: Product::PARAM_TITLE, type: 'integer', example: 'Хтонь за околицей. Книга первая'),
        new OA\Property(property: Model::UPDATED_AT, type: 'string', example: '2026-09-10 10:10:19'),
        new OA\Property(property: Model::CREATED_AT, type: 'string', example: '2026-09-10 10:10:19'),
        // > relations
        new OA\Property(
            property: Product::PARAM_SHOP,
            ref: '#/components/schemas/ShopResource',
            type: 'object'
        ),
        new OA\Property(
            property: Product::PARAM_CITY,
            ref: '#/components/schemas/CityResource',
            type: 'object'
        ),
        new OA\Property(
            property: Product::PARAM_BOOK,
            ref: '#/components/schemas/BookResource',
            type: 'object'
        ),
        new OA\Property(
            property: Product::PARAM_SOURCE_PRODUCT,
            ref: '#/components/schemas/SourceProductResource',
            type: 'object'
        ),
        // < relations
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'ProductDataResource',
    properties: [
        new OA\Property(
            property: 'data',
            ref: '#/components/schemas/ProductResource',
            type: 'object'
        ),
    ],
    type: 'object'
)]
class ProductResource extends AbstractResource
{
    public function getArrayData(Request $request): array
    {
        $book = $this->source instanceof Book ? $this->source : null;
        $sourceProduct = $this->source instanceof SourceProduct ? $this->source : null;

        return [
            Product::PARAM_ID => $this->offsetGet(Product::PARAM_ID),
            Product::PARAM_SHOP_PRODUCT_ID => $this->offsetGet(Product::PARAM_SHOP_PRODUCT_ID),
            Product::PARAM_SHOP_PRODUCT_CODE => $this->offsetGet(Product::PARAM_SHOP_PRODUCT_CODE),
            Product::PARAM_TITLE => $this->offsetGet(Product::PARAM_TITLE),

            Model::UPDATED_AT => Carbon::createFromDate($this->updated_at)->format('Y-m-d H:i:s'),
            Model::CREATED_AT => Carbon::createFromDate($this->created_at)->format('Y-m-d H:i:s'),

            // > relations
            Product::PARAM_SHOP => ShopResource::make($this->shop),
            Product::PARAM_CITY => CityResource::make($this->city),

            Product::PARAM_BOOK => $book ? BookResource::make($book) : null,
            Product::PARAM_SOURCE_PRODUCT => $sourceProduct ? SourceProductResource::make($sourceProduct) : null,
            // < relations
        ];
    }
}
