<?php

namespace App\Http\Resources\PublishingBrand;

use App\Http\Resources\CommonResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PublishingBrandResourceCollection',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/PublishingBrandResource',
                type: 'object'
            )
        ),
    ],
    type: 'object'
)]
class PublishingBrandResourceCollection extends CommonResourceCollection {}
