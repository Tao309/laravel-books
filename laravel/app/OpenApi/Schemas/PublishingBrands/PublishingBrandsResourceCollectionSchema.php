<?php

namespace App\OpenApi\Schemas\PublishingBrands;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    description: 'Collection of PublishingBrands',
    items: new OA\Items(
        ref: Refs::PUBLISHING_BRAND_RESOURCE,
        type: 'object'
    ),
    schema: 'PublishingBrandsResourceCollection',
    type: 'object'
)]
#[OA\Schema(
    properties: [
        new OA\Property(
            items: new OA\Items(
                ref: Refs::PUBLISHING_BRAND_RESOURCE,
                type: 'object'
            ),
            property: 'data',
            type: 'array'
        ),
        new OA\Property(example: true, property: 'success', type: 'bool'),
    ],
    schema: 'PublishingBrandsDataResourceCollection',
    type: 'object'
)]
class PublishingBrandsResourceCollectionSchema {}
