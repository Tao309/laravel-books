<?php

namespace App\Http\Resources\PublishingBrand;

use App\Http\Resources\AbstractResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PublishingBrandsResourceCollection',
    description: 'Collection of PublishingBrands',
    type: 'object',
    items: new OA\Items(
        ref: '#/components/schemas/PublishingBrandResource',
        type: 'object'
    )
)]
#[OA\Schema(
    schema: 'PublishingBrandsDataResourceCollection',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/PublishingBrandResource',
                type: 'object'
            )
        ),
        new OA\Property(property: 'success', type: 'bool', example: true),
    ],
    type: 'object'
)]
class PublishingBrandsResourceCollection extends AbstractResourceCollection
{
    public $collects = PublishingBrandResource::class;
}
