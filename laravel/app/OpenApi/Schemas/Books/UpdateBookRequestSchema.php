<?php

namespace App\OpenApi\Schemas\Books;

use App\Models\Book;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateBookRequest',
    properties: [
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
        new OA\Property(
            property: Book::PARAM_BINDING_TYPE,
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: '1'),
            ],
            type: 'object'
        ),
        new OA\Property(
            property: Book::PARAM_PAPER_TYPE,
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: '1'),
            ],
            type: 'object'
        ),
        new OA\Property(
            property: Book::PARAM_AUTHORS,
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'id', type: 'integer', example: '1'),
                ],
                type: 'object'
            )
        ),
        new OA\Property(
            property: Book::PARAM_PUBLISHING_HOUSE,
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: '1'),
            ],
            type: 'object'
        ),
        new OA\Property(
            property: Book::PARAM_PUBLISHING_BRAND,
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: '1'),
            ],
            type: 'object'
        ),
        new OA\Property(
            property: Book::PARAM_BOOK_SERIES,
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: '1'),
            ],
            type: 'object'
        ),
    ],
    type: 'object'
)]
class UpdateBookRequestSchema {}
