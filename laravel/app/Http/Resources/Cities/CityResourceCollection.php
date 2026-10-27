<?php

namespace App\Http\Resources\Cities;

use App\Http\Resources\CommonResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CityCollection',
    description: 'Collection of Cities',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/CityResource',
                type: 'object'
            )
        ),
    ],
    type: 'object'
)]
class CityResourceCollection extends CommonResourceCollection {}
