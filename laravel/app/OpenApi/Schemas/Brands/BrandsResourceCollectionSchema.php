<?php

namespace App\OpenApi\Schemas\Brands;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'BrandsResourceCollection',
    description: 'Collection of Brands',
    type: 'object',
    items: new OA\Items(
        ref: Refs::BRAND_RESOURCE,
        type: 'object'
    )
)]
#[OA\Schema(
    schema: 'BrandsPaginateResourceCollection',
    description: 'Collection of Brands with paginate',
    allOf: [
        new OA\Schema(ref: Refs::RESPONSE),
        new OA\Schema(ref: Refs::PAGINATE_RESPONSE),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    ref: Refs::BRANDS_RESOURCE_COLLECTION,
                    type: 'object'
                ),
            ],
            type: 'object'
        ),
    ]
)]
class BrandsResourceCollectionSchema {}
