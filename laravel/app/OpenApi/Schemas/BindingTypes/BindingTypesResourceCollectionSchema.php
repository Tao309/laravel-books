<?php

namespace App\OpenApi\Schemas\BindingTypes;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'BindingTypesResourceCollection',
    description: 'Collection of binding types',
    type: 'object',
    items: new OA\Items(
        ref: Refs::BINDING_TYPE_RESOURCE,
        type: 'object'
    )
)]
#[OA\Schema(
    schema: 'BindingTypesIndexResponse',
    description: 'Collection of BindingTypes',
    allOf: [
        new OA\Schema(ref: Refs::RESPONSE),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    ref: Refs::BINDING_TYPES_RESOURCE_COLLECTION,
                    type: 'object'
                ),
            ],
            type: 'object'
        ),
    ]
)]
class BindingTypesResourceCollectionSchema {}
