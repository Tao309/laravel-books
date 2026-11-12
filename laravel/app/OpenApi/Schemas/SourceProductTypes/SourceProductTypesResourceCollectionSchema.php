<?php

namespace App\OpenApi\Schemas\SourceProductTypes;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    description: 'Collection of SourceProductTypes',
    items: new OA\Items(
        ref: Refs::SOURCE_PRODUCT_TYPE_RESOURCE,
        type: 'object'
    ),
    schema: 'SourceProductTypesResourceCollection',
    type: 'object'
)]
#[OA\Schema(
    description: 'Collection of SourceProductTypes',
    properties: [
        new OA\Property(
            items: new OA\Items(
                ref: Refs::SOURCE_PRODUCT_TYPE_RESOURCE,
                type: 'object'
            ),
            property: 'data',
            type: 'array'
        ),
        new OA\Property(example: true, property: 'success', type: 'bool'),
    ],
    schema: 'SourceProductTypesDataResourceCollection',
    type: 'object'
)]
class SourceProductTypesResourceCollectionSchema {}
