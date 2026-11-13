<?php

namespace App\OpenApi\Schemas\Products;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductsResourceCollection',
    description: 'Collection of Products',
    type: 'object',
    items: new OA\Items(
        ref: Refs::PRODUCT_RESOURCE,
        type: 'object'
    )
)]
#[OA\Schema(
    schema: 'ProductsPaginateResourceCollection',
    description: 'Collection of Products with paginate',
    type: 'object',
    allOf: [
        new OA\Schema(ref: Refs::RESPONSE),
        new OA\Schema(ref: Refs::PAGINATE_RESPONSE),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    ref: Refs::PRODUCTS_RESOURCE_COLLECTION,
                    type: 'object'
                ),
            ],
            type: 'object'
        ),
    ],
)]
class ProductsResourceCollectionSchema {}
