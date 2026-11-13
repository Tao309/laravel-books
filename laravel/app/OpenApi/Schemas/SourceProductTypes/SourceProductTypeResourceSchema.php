<?php

namespace App\OpenApi\Schemas\SourceProductTypes;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SourceProductTypeResource',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: '1'),
        new OA\Property(property: 'code', type: 'string', example: 'toys'),
        new OA\Property(property: 'name', type: 'string', example: 'Игрушки'),
        new OA\Property(property: 'updated_at', type: 'string', example: '2026-09-10 10:10:19'),
        new OA\Property(property: 'created_at', type: 'string', example: '2026-09-10 10:10:19'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'SourceProductTypeDataResource',
    description: 'Single SourceProductType response',
    allOf: [
        new OA\Schema(ref: Refs::RESPONSE),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    ref: Refs::SOURCE_PRODUCT_TYPE_RESOURCE,
                    type: 'object'
                ),
            ],
            type: 'object'
        ),
    ]
)]
#[OA\Schema(
    schema: 'SourceProductTypeDestroyResponse',
    description: 'Delete SourceProductType response',
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
class SourceProductTypeResourceSchema {}
