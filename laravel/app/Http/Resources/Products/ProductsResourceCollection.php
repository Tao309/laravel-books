<?php

namespace App\Http\Resources\Products;

use App\Http\Resources\AbstractResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductsResourceCollection',
    description: 'Collection of Products',
    type: 'object',
    items: new OA\Items(
        ref: '#/components/schemas/ProductResource',
        type: 'object'
    )
)]
#[OA\Schema(
    schema: 'ProductsDataResourceCollection',
    description: 'Collection of Products',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/ProductResource',
                type: 'object'
            )
        ),
    ],
    type: 'object'
)]
class ProductsResourceCollection extends AbstractResourceCollection
{
    public $collects = ProductResource::class;
}
