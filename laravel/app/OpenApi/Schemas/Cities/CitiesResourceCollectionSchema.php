<?php

namespace App\OpenApi\Schemas\Cities;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CitiesResourceCollection',
    description: 'Collection of Cities',
    type: 'object',
    items: new OA\Items(
        ref: Refs::CITY_RESOURCE,
        type: 'object'
    )
)]
#[OA\Schema(
    schema: 'CitiesDataResourceCollection',
    description: 'Collection of Cities',
    allOf: [
        new OA\Schema(ref: Refs::RESPONSE),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(
                        ref: Refs::CITY_RESOURCE,
                        type: 'object'
                    )
                ),
            ],
            type: 'object'
        ),
    ]
)]
class CitiesResourceCollectionSchema {}
