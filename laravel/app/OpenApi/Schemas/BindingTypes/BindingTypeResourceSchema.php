<?php

namespace App\OpenApi\Schemas\BindingTypes;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'BindingTypeResource',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: '1'),
        new OA\Property(property: 'label', type: 'string', example: 'Мягкий переплёт'),
        new OA\Property(property: 'updated_at', type: 'string', example: '2026-09-10 10:10:19'),
        new OA\Property(property: 'created_at', type: 'string', example: '2026-09-10 10:10:19'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'BindingTypeDataResource',
    description: 'Single BindingType response',
    allOf: [
        new OA\Schema(ref: Refs::RESPONSE),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    ref: Refs::BINDING_TYPE_RESOURCE,
                    type: 'object'
                ),
            ],
            type: 'object'
        ),
    ]
)]
#[OA\Schema(
    schema: 'BindingTypeDestroyResponse',
    description: 'Delete BindingType response',
    allOf: [
        new OA\Schema(ref: Refs::RESPONSE),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    properties: [
                        new OA\Property(property: 'success', type: 'bool', example: true),
                    ],
                    type: 'object'
                ),
            ],
            type: 'object'
        ),
    ]
)]
class BindingTypeResourceSchema {}
