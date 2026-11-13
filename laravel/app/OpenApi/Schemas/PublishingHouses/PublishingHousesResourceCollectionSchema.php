<?php

namespace App\OpenApi\Schemas\PublishingHouses;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PublishingHousesResourceCollection',
    description: 'Collection of PublishingHouses',
    type: 'object',
    items: new OA\Items(
        ref: Refs::PUBLISHING_HOUSE_RESOURCE,
        type: 'object'
    )
)]
#[OA\Schema(
    schema: 'PublishingHousesIndexResponse',
    description: 'Collection of PublishingHouses',
    allOf: [
        new OA\Schema(ref: Refs::RESPONSE),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    ref: Refs::PUBLISHING_HOUSES_RESOURCE_COLLECTION,
                    type: 'object'
                ),
            ],
            type: 'object'
        ),
    ]
)]
class PublishingHousesResourceCollectionSchema {}
