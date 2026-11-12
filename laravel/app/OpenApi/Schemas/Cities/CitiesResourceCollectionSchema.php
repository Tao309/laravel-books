<?php

namespace App\OpenApi\Schemas\Cities;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    description: 'Collection of Cities',
    items: new OA\Items(
        ref: Refs::CITY_RESOURCE,
        type: 'object'
    ),
    schema: 'CitiesResourceCollection',
    type: 'object'
)]
#[OA\Schema(
    description: 'Collection of Cities',
    properties: [
        new OA\Property(
            items: new OA\Items(
                ref: Refs::CITY_RESOURCE,
                type: 'object'
            ),
            property: 'data',
            type: 'array'
        ),
        new OA\Property(example: true, property: 'success', type: 'bool'),
    ],
    schema: 'CitiesDataResourceCollection',
    type: 'object'
)]
class CitiesResourceCollectionSchema {}
