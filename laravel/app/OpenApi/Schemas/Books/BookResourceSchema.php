<?php

namespace App\OpenApi\Schemas\Books;

use App\Models\Book;
use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'BookResource',
    properties: [
        new OA\Property(property: Book::PARAM_ID, type: 'integer', example: '1'),
        new OA\Property(property: Book::PARAM_TITLE, type: 'string', example: 'Сияние'),
        new OA\Property(property: Book::PARAM_ORIGINAL_TITLE, type: 'string', example: 'The Shining'),
        new OA\Property(property: Book::PARAM_ISBN, type: 'string', example: '978-5-17-080493-1'),
        new OA\Property(property: Book::PARAM_PAGES, type: 'integer', example: '544'),
        new OA\Property(property: Book::PARAM_CIRCULATION, type: 'integer', example: '21000'),
        new OA\Property(property: Book::PARAM_SIZE, type: 'integer', example: '4x14x21'),
        new OA\Property(property: Book::PARAM_PUBLISH_YEAR, type: 'integer', example: '2025'),
        new OA\Property(property: Book::PARAM_LIVELIB_ID, type: 'integer', example: '1000965735'),
        new OA\Property(property: Book::PARAM_GOODREADS_ID, type: 'integer', example: '11588'),
        new OA\Property(property: Book::PARAM_FANTLAB_ID, type: 'integer', example: 'edition115331'),
        new OA\Property(property: Book::PARAM_LIVELIB_RATING, type: 'float', example: '4.4'),
        new OA\Property(property: Book::PARAM_GOODREADS_RATING, type: 'float', example: '4.28'),
        new OA\Property(property: 'updated_at', type: 'string', example: '2026-09-10 10:10:19'),
        new OA\Property(property: 'created_at', type: 'string', example: '2026-09-10 10:10:19'),
        new OA\Property(
            property: Book::PARAM_BINDING_TYPE,
            ref: Refs::BINDING_TYPE_RESOURCE,
            type: 'object'
        ),
        new OA\Property(
            property: Book::PARAM_PAPER_TYPE,
            ref: Refs::PAPER_TYPE_RESOURCE,
            type: 'object'
        ),
        new OA\Property(
            property: Book::PARAM_AUTHORS,
            type: 'array',
            items: new OA\Items(
                ref: Refs::AUTHOR_RESOURCE,
                type: 'object'
            )
        ),
        new OA\Property(
            property: Book::PARAM_PUBLISHING_HOUSE,
            ref: Refs::PUBLISHING_HOUSE_RESOURCE,
            type: 'object'
        ),
        new OA\Property(
            property: Book::PARAM_PUBLISHING_BRAND,
            ref: Refs::PUBLISHING_BRAND_RESOURCE,
            type: 'object'
        ),
        new OA\Property(
            property: Book::PARAM_BOOK_SERIES,
            ref: Refs::BOOK_SERIES_RESOURCE,
            type: 'object'
        ),
        new OA\Property(
            property: Book::PARAM_USER_CREATED,
            ref: Refs::USER_RESOURCE,
            type: 'object'
        ),
        new OA\Property(
            property: Book::PARAM_BOOK_USER_DATA,
            ref: Refs::BOOK_USER_DATA_RESOURCE,
            type: 'object'
        ),
    ],
    type: 'object',
)]
#[OA\Schema(
    schema: 'BookDataResource',
    description: 'Single Book response',
    allOf: [
        new OA\Schema(ref: Refs::RESPONSE),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    ref: Refs::BOOK_RESOURCE,
                    type: 'object'
                ),
            ],
            type: 'object'
        ),
    ]
 )]
#[OA\Schema(
    schema: 'BookDestroyResponse',
    description: 'Delete Book response',
    allOf: [
        new OA\Schema(ref: Refs::RESPONSE),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    properties: [
                        new OA\Property(property: 'success', type: 'bool', example: true),
                    ],
                    type: 'object'
                ),
            ],
            type: 'object'
        ),
    ]
)]
class BookResourceSchema {}
