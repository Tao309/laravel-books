<?php

namespace App\OpenApi\Schemas\Books;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'BooksResourceCollection',
    description: 'Collection of books',
    type: 'object',
    items: new OA\Items(
        ref: Refs::BOOK_RESOURCE,
        type: 'object'
    )
)]
#[OA\Schema(
    schema: 'BooksPaginateResourceCollection',
    description: 'Collection of books with paginate',
    type: 'object',
    allOf: [
        new OA\Schema(ref: Refs::RESPONSE),
        new OA\Schema(ref: Refs::PAGINATE_RESPONSE),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    ref: Refs::BOOKS_RESOURCE_COLLECTION,
                    type: 'object'
                ),
            ],
            type: 'object'
        ),
    ]
)]
class BooksResourceCollectionSchema {}
