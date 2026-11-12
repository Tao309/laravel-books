<?php

namespace App\OpenApi\Schemas\Authors;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    description: 'Collection of Authors',
    items: new OA\Items(
        ref: Refs::AUTHOR_RESOURCE,
        type: 'object'
    ),
    schema: 'AuthorsResourceCollection',
    type: 'object'
)]
#[OA\Schema(
    description: 'Collection of Authors',
    properties: [
        new OA\Property(
            items: new OA\Items(
                ref: Refs::AUTHOR_RESOURCE,
                type: 'object'
            ),
            property: 'data',
            type: 'array'
        ),
        new OA\Property(example: true, property: 'success', type: 'bool'),
    ],
    schema: 'AuthorsDataResourceCollection',
    type: 'object'
)]
#[OA\Schema(
    description: 'Collection of Authors with paginate',
    properties: [
        new OA\Property(example: 1, property: 'current_page', type: 'integer'),
        new OA\Property(
            items: new OA\Items(
                ref: Refs::AUTHOR_RESOURCE,
                type: 'object'
            ),
            property: 'items',
            type: 'array'
        ),
        new OA\Property(example: 'http://localhost/api/models?page=1', property: 'first_page_url', type: 'string'),
        new OA\Property(example: 1, property: 'from', type: 'integer'),
        new OA\Property(example: 1, property: 'last_page', type: 'integer'),
        new OA\Property(example: 'http://localhost/api/models?page=1', property: 'last_page_url', type: 'string'),
        new OA\Property(example: 'http://localhost/api/models?page=2', property: 'next_page_url', type: 'string'),
        new OA\Property(example: 'http://localhost/api/models', property: 'path', type: 'string'),
        new OA\Property(example: 100, property: 'per_page', type: 'integer'),
        new OA\Property(example: 'http://localhost/api/models?page=1', property: 'prev_page_url', type: 'string'),
        new OA\Property(example: 3, property: 'to', type: 'integer'),
        new OA\Property(example: 3, property: 'total', type: 'integer'),
        new OA\Property(example: true, property: 'success', type: 'bool'),
    ],
    schema: 'AuthorsPaginateResourceCollection',
    type: 'object'
)]
class AuthorsResourceCollectionSchema {}
