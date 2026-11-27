<?php

namespace App\OpenApi\Schemas\Brands;

use App\Models\Brand;
use App\OpenApi\Refs;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'BrandResource',
    properties: [
        new OA\Property(property: Brand::PARAM_ID, type: 'integer', example: 1),
        new OA\Property(property: Brand::PARAM_CODE, type: 'string', example: 'joy-toy'),
        new OA\Property(property: Brand::PARAM_TITLE, type: 'string', example: 'JoyToy'),
        new OA\Property(property: Model::UPDATED_AT, type: 'string', example: '2026-09-10 10:10:19'),
        new OA\Property(property: Model::CREATED_AT, type: 'string', example: '2026-09-10 10:10:19'),
    ],
    type: 'object'
)]
#[OA\Schema(
    ref: Refs::RESPONSE,
    schema: 'BrandDestroyResponse',
    description: 'Delete Brand response',
)]
#[OA\Schema(
    schema: 'BrandShowResponse',
    allOf: [
        new OA\Schema(ref: Refs::RESPONSE),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    ref: Refs::BRAND_RESOURCE,
                    type: 'object'
                ),
            ],
            type: 'object'
        ),
    ]
)]
class BrandResourceSchema {}
