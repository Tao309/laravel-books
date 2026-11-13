<?php

namespace App\OpenApi\Schemas\BindingTypes;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'BindingTypesCollection',
    description: 'Collection of binding types',
    type: 'object',
    items: new OA\Items(
        ref: Refs::BINDING_TYPE_RESOURCE,
        type: 'object'
    )
)]
#[OA\Schema(
    schema: 'BindingTypesDataResourceCollection',
    description: 'Collection of binding types',
    allOf: [
        new OA\Schema(ref: Refs::RESPONSE),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(
                        ref: Refs::BINDING_TYPE_RESOURCE,
                        type: 'object'
                    )
                ),
            ],
            type: 'object'
        ),
    ]
)]
class BindingTypesResourceCollectionSchema {}
