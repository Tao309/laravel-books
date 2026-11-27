<?php

namespace App\OpenApi\Schemas\Brands;

use App\Models\Brand;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreBrandRequest',
    properties: [
        new OA\Property(property: Brand::PARAM_CODE, type: 'string', example: 'joy-toy'),
        new OA\Property(property: Brand::PARAM_TITLE, type: 'string', example: 'JoyToy'),
    ],
    type: 'object'
)]
class StoreBrandRequestSchema {}
