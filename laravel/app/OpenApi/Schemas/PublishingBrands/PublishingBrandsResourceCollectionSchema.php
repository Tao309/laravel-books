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
    schema: 'PublishingBrandsIndexResponse',
    description: 'Collection of PublishingBrands',
    allOf: [
        new OA\Schema(ref: Refs::RESPONSE),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    ref: Refs::PUBLISHING_BRANDS_RESOURCE_COLLECTION,
                    type: 'object'
                ),
            ],
            type: 'object'
        ),
    ]
)]
class PublishingBrandsResourceCollectionSchema {}
