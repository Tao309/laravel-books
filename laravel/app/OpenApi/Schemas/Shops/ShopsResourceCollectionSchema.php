<?php

namespace App\OpenApi\Schemas\Shops;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopsResourceCollection',
    description: 'Collection of Shops',
    type: 'object',
    items: new OA\Items(
        ref: Refs::SHOP_RESOURCE,
        type: 'object'
    )
)]
#[OA\Schema(
    schema: 'ShopsDataResourceCollection',
    description: 'Collection of Shops',
    allOf: [
        new OA\Schema(ref: Refs::RESPONSE),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(
                        ref: Refs::SHOP_RESOURCE,
                        type: 'object'
                    )
                ),
            ],
            type: 'object'
        ),
    ]
)]
class ShopsResourceCollectionSchema {}
