<?php

namespace App\OpenApi\Schemas\Authors;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AuthorsResourceCollection',
    description: 'Collection of Authors',
    type: 'object',
    items: new OA\Items(
        ref: Refs::AUTHOR_RESOURCE,
        type: 'object'
    )
)]
#[OA\Schema(
    schema: 'AuthorsPaginateResourceCollection',
    description: 'Collection of Authors with paginate',
    allOf: [
        new OA\Schema(ref: Refs::RESPONSE),
        new OA\Schema(ref: Refs::PAGINATE_RESPONSE),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    ref: Refs::AUTHORS_RESOURCE_COLLECTION,
                    type: 'object'
                ),
            ],
            type: 'object'
        ),
    ]
)]
class AuthorsResourceCollectionSchema {}
