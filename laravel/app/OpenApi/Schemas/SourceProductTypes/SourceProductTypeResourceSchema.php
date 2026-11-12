<?php

namespace App\OpenApi\Schemas\SourceProductTypes;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(example: '1', property: 'id', type: 'integer'),
        new OA\Property(example: 'toys', property: 'code', type: 'string'),
        new OA\Property(example: 'Игрушки', property: 'name', type: 'string'),
        new OA\Property(example: '2026-09-10 10:10:19', property: 'updated_at', type: 'string'),
        new OA\Property(example: '2026-09-10 10:10:19', property: 'created_at', type: 'string'),
    ],
    schema: 'SourceProductTypeResource',
    type: 'object'
)]
#[OA\Schema(
    properties: [
        new OA\Property(
            property: 'data',
            ref: Refs::SOURCE_PRODUCT_TYPE_RESOURCE,
            type: 'object'
        ),
        new OA\Property(example: true, property: 'success', type: 'bool'),
    ],
    schema: 'SourceProductTypeDataResource',
    type: 'object'
)]
class SourceProductTypeResourceSchema {}
