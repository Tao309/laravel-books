<?php

namespace App\Http\Resources\Cities;

use App\Http\Resources\AbstractResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CitiesResourceCollection',
    description: 'Collection of Cities',
    type: 'object',
    items: new OA\Items(
        ref: '#/components/schemas/CityResource',
        type: 'object'
    )
)]
#[OA\Schema(
    schema: 'CitiesDataResourceCollection',
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
        new OA\Property(property: 'success', type: 'bool', example: true),
    ],
    type: 'object'
)]
class CitiesResourceCollection extends AbstractResourceCollection
{
    public $collects = CityResource::class;
}
