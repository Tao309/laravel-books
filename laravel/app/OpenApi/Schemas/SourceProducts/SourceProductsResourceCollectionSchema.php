<?php

namespace App\OpenApi\Schemas\SourceProducts;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    description: 'Collection of SourceProducts',
    items: new OA\Items(
        ref: Refs::SOURCE_PRODUCT_RESOURCE,
        type: 'object'
    ),
    schema: 'SourceProductsResourceCollection',
    type: 'object'
)]
#[OA\Schema(
    description: 'Collection of SourceProducts',
    properties: [
        new OA\Property(
            items: new OA\Items(
                ref: Refs::SOURCE_PRODUCT_RESOURCE,
                type: 'object'
            ),
            property: 'data',
            type: 'array'
        ),
        new OA\Property(example: true, property: 'success', type: 'bool'),
    ],
    schema: 'SourceProductsDataResourceCollection',
    type: 'object'
)]
class SourceProductsResourceCollectionSchema {}
