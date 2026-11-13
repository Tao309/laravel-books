<?php

namespace App\OpenApi\Schemas\PublishingBrands;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PublishingBrandsResourceCollection',
    description: 'Collection of PublishingBrands',
    type: 'object',
    items: new OA\Items(
        ref: Refs::PUBLISHING_BRAND_RESOURCE,
        type: 'object'
    )
)]
#[OA\Schema(
    schema: 'PublishingBrandsDataResourceCollection',
    allOf: [
        new OA\Schema(ref: Refs::RESPONSE),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(
                        ref: Refs::PUBLISHING_BRAND_RESOURCE,
                        type: 'object'
                    )
                ),
            ],
            type: 'object'
        ),
    ]
)]
class PublishingBrandsResourceCollectionSchema {}
