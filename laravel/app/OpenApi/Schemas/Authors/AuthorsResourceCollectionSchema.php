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
    schema: 'AuthorsDataResourceCollection',
    description: 'Collection of Authors',
    allOf: [
        new OA\Schema(ref: Refs::RESPONSE),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(
                        ref: Refs::AUTHOR_RESOURCE,
                        type: 'object'
                    )
                ),
            ],
            type: 'object'
        ),
    ]
)]
#[OA\Schema(
    schema: 'AuthorsPaginateResourceCollection',
    description: 'Collection of Authors with paginate',
    properties: [
        new OA\Property(property: 'current_page', type: 'integer', example: 1),
        new OA\Property(
            property: 'items',
            type: 'array',
            items: new OA\Items(
                ref: Refs::AUTHOR_RESOURCE,
                type: 'object'
            )
        ),
        new OA\Property(property: 'first_page_url', type: 'string', example: 'http://localhost/api/models?page=1'),
        new OA\Property(property: 'from', type: 'integer', example: 1),
        new OA\Property(property: 'last_page', type: 'integer', example: 1),
        new OA\Property(property: 'last_page_url', type: 'string', example: 'http://localhost/api/models?page=1'),
        new OA\Property(property: 'next_page_url', type: 'string', example: 'http://localhost/api/models?page=2'),
        new OA\Property(property: 'path', type: 'string', example: 'http://localhost/api/models'),
        new OA\Property(property: 'per_page', type: 'integer', example: 100),
        new OA\Property(property: 'prev_page_url', type: 'string', example: 'http://localhost/api/models?page=1'),
        new OA\Property(property: 'to', type: 'integer', example: 3),
        new OA\Property(property: 'total', type: 'integer', example: 3),
        new OA\Property(property: 'success', type: 'bool', example: true),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'AuthorsIndexResponse',
    description: 'Paginated list of Authors',
    allOf: [
        new OA\Schema(ref: Refs::RESPONSE),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    ref: Refs::AUTHORS_PAGINATE_RESOURCE_COLLECTION,
                    type: 'object'
                ),
            ],
            type: 'object'
        ),
    ]
)]
class AuthorsResourceCollectionSchema {}
