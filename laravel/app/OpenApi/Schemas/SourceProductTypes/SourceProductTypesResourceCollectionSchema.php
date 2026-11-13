<?php

namespace App\OpenApi\Schemas\SourceProductTypes;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SourceProductTypesResourceCollection',
    description: 'Collection of SourceProductTypes',
    type: 'object',
    items: new OA\Items(
        ref: Refs::SOURCE_PRODUCT_TYPE_RESOURCE,
        type: 'object'
    )
)]
#[OA\Schema(
    schema: 'SourceProductTypesDataResourceCollection',
    description: 'Collection of SourceProductTypes',
    allOf: [
        new OA\Schema(ref: Refs::RESPONSE),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(
                        ref: Refs::SOURCE_PRODUCT_TYPE_RESOURCE,
                        type: 'object'
                    )
                ),
            ],
            type: 'object'
        ),
    ]
)]
class SourceProductTypesResourceCollectionSchema {}
