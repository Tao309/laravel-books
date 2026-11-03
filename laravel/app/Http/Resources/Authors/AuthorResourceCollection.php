<?php

namespace App\Http\Resources\Authors;

use App\Http\Resources\AbstractResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AuthorResourceCollection',
    description: 'Collection of authors',
    type: 'object',
    items: new OA\Items(
        ref: '#/components/schemas/AuthorResource',
        type: 'object'
    )
)]
#[OA\Schema(
    schema: 'AuthorDataResourceCollection',
    description: 'Collection of authors',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/AuthorResource',
                type: 'object'
            )
        ),
    ],
    type: 'object'
)]
class AuthorResourceCollection extends AbstractResourceCollection {}
