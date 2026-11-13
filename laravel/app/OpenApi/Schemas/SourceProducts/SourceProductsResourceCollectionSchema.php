<?php

namespace App\OpenApi\Schemas\SourceProducts;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SourceProductsResourceCollection',
    description: 'Collection of SourceProducts',
    type: 'object',
    items: new OA\Items(
        ref: Refs::SOURCE_PRODUCT_RESOURCE,
        type: 'object'
    )
)]
#[OA\Schema(
    schema: 'SourceProductsDataResourceCollection',
    description: 'Collection of SourceProducts',
    allOf: [
        new OA\Schema(ref: Refs::RESPONSE),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(
                        ref: Refs::SOURCE_PRODUCT_RESOURCE,
                        type: 'object'
                    )
                ),
            ],
            type: 'object'
        ),
    ]
)]
class SourceProductsResourceCollectionSchema {}
