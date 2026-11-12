<?php

namespace App\OpenApi\Schemas\Books;

use App\Models\Book;
use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'BookResource',
    properties: [
        new OA\Property(example: '1', property: Book::PARAM_ID, type: 'integer'),
        new OA\Property(example: 'Сияние', property: Book::PARAM_TITLE, type: 'string'),
        new OA\Property(example: 'The Shining', property: Book::PARAM_ORIGINAL_TITLE, type: 'string'),
        new OA\Property(example: '978-5-17-080493-1', property: Book::PARAM_ISBN, type: 'string'),
        new OA\Property(example: '544', property: Book::PARAM_PAGES, type: 'integer'),
        new OA\Property(example: '21000', property: Book::PARAM_CIRCULATION, type: 'integer'),
        new OA\Property(example: '4x14x21', property: Book::PARAM_SIZE, type: 'integer'),
        new OA\Property(example: '2025', property: Book::PARAM_PUBLISH_YEAR, type: 'integer'),
        new OA\Property(example: '1000965735', property: Book::PARAM_LIVELIB_ID, type: 'integer'),
        new OA\Property(example: '11588', property: Book::PARAM_GOODREADS_ID, type: 'integer'),
        new OA\Property(example: 'edition115331', property: Book::PARAM_FANTLAB_ID, type: 'integer'),
        new OA\Property(example: '4.4', property: Book::PARAM_LIVELIB_RATING, type: 'float'),
        new OA\Property(example: '4.28', property: Book::PARAM_GOODREADS_RATING, type: 'float'),
        new OA\Property(example: '2026-09-10 10:10:19', property: 'updated_at', type: 'string'),
        new OA\Property(example: '2026-09-10 10:10:19', property: 'created_at', type: 'string'),
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
    properties: [
        new OA\Property(
            property: 'data',
            ref: Refs::BOOK_RESOURCE,
            type: 'object'
        ),
        new OA\Property(example: true, property: 'success', type: 'bool'),
    ],
    type: 'object'
)]
class BookResourceSchema {}
