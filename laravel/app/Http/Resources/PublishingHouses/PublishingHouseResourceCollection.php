<?php

namespace App\Http\Resources\PublishingHouses;

use App\Http\Resources\CommonResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PublishingHouseResourceCollection',
    description: 'Collection of Publishing Houses',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/PublishingHouseResource',
                type: 'object'
            )
        ),
    ],
    type: 'object'
)]
class PublishingHouseResourceCollection extends CommonResourceCollection {}
