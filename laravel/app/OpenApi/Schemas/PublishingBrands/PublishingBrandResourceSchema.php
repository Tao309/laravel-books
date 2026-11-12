<?php

namespace App\OpenApi\Schemas\PublishingBrands;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(example: '1', property: 'id', type: 'integer'),
        new OA\Property(example: 'Астрель СПб', property: 'name', type: 'string'),
        new OA\Property(example: '2026-09-10 10:10:19', property: 'updated_at', type: 'string'),
        new OA\Property(example: '2026-09-10 10:10:19', property: 'created_at', type: 'string'),
    ],
    schema: 'PublishingBrandResource',
    type: 'object'
)]
#[OA\Schema(
    properties: [
        new OA\Property(
            property: 'data',
            ref: Refs::PUBLISHING_BRAND_RESOURCE,
            type: 'object'
        ),
        new OA\Property(example: true, property: 'success', type: 'bool'),
    ],
    schema: 'PublishingBrandDataResource',
    type: 'object'
)]
class PublishingBrandResourceSchema {}
