<?php

namespace App\Http\Resources\BindingTypes;

use App\Http\Resources\CommonCollection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'BindingTypeCollection',
    description: 'Collection of binding types',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/BindingTypeResource',
                type: 'object'
            )
        ),
    ],
    type: 'object'
)]
class BindingTypeCollection extends CommonCollection {}
