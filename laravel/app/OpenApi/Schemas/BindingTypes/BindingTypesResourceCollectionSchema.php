<?php

namespace App\OpenApi\Schemas\BindingTypes;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    description: 'Collection of binding types',
    items: new OA\Items(
        ref: Refs::BINDING_TYPE_RESOURCE,
        type: 'object'
    ),
    schema: 'BindingTypesCollection',
    type: 'object'
)]
#[OA\Schema(
    description: 'Collection of binding types',
    properties: [
        new OA\Property(
            items: new OA\Items(
                ref: Refs::BINDING_TYPE_RESOURCE,
                type: 'object'
            ),
            property: 'data',
            type: 'array'
        ),
        new OA\Property(example: true, property: 'success', type: 'bool'),
    ],
    schema: 'BindingTypesDataResourceCollection',
    type: 'object'
)]
class BindingTypesResourceCollectionSchema {}
