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
    schema: 'BooksResourceDataCollection',
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
    ],
    type: 'object'
)]
class BooksResourceCollection extends AbstractResourceCollection
{
    public $collects = BookResource::class;
}
