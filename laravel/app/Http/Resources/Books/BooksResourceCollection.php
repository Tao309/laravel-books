<?php

namespace App\Http\Resources\Books;

use App\Http\Resources\AbstractResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'BooksResourceCollection',
    description: 'Collection of books',
    type: 'object',
    items: new OA\Items(
        ref: '#/components/schemas/BookResource',
        type: 'object'
    )
)]
#[OA\Schema(
    schema: 'BooksDataResourceCollection',
    description: 'Collection of books',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/BookResource',
                type: 'object'
            )
        ),
        new OA\Property(property: 'success', type: 'bool', example: true),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'BooksPaginateResourceCollection',
    description: 'Collection of books with paginate',
    properties: [
        new OA\Property(property: 'current_page', type: 'integer', example: 1),
        new OA\Property(
            property: 'items',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/BookResource',
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
class BooksResourceCollection extends AbstractResourceCollection
{
    public $collects = BookResource::class;
}
