<?php

namespace App\OpenApi\Schemas\PublishingHouses;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    description: 'Collection of PublishingHouses',
    items: new OA\Items(
        ref: Refs::PUBLISHING_HOUSE_RESOURCE,
        type: 'object'
    ),
    schema: 'PublishingHousesResourceCollection',
    type: 'object'
)]
#[OA\Schema(
    description: 'Collection of PublishingHouses',
    properties: [
        new OA\Property(
            items: new OA\Items(
                ref: Refs::PUBLISHING_HOUSE_RESOURCE,
                type: 'object'
            ),
            property: 'data',
            type: 'array'
        ),
        new OA\Property(example: true, property: 'success', type: 'bool'),
    ],
    schema: 'PublishingHousesDataResourceCollection',
    type: 'object'
)]
class PublishingHousesResourceCollectionSchema {}
