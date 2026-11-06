<?php

namespace App\Http\Resources\PublishingHouses;

use App\Http\Resources\AbstractResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PublishingHousesResourceCollection',
    description: 'Collection of PublishingHouses',
    type: 'object',
    items: new OA\Items(
        ref: '#/components/schemas/PublishingHouseResource',
        type: 'object'
    )
)]
#[OA\Schema(
    schema: 'PublishingHousesDataResourceCollection',
    description: 'Collection of PublishingHouses',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/PublishingHouseResource',
                type: 'object'
            )
        ),
        new OA\Property(property: 'success', type: 'bool', example: true),
    ],
    type: 'object'
)]
class PublishingHousesResourceCollection extends AbstractResourceCollection
{
    public $collects = PublishingHouseResource::class;
}
