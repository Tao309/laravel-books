<?php

namespace App\OpenApi\Schemas\Books;

use App\Models\Book;
use App\Models\BookUserData;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
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
        new OA\Property(
            properties: [
                new OA\Property(example: '1', property: 'id', type: 'integer'),
            ],
            property: Book::PARAM_BINDING_TYPE,
            type: 'object'
        ),
        new OA\Property(
            properties: [
                new OA\Property(example: '1', property: 'id', type: 'integer'),
            ],
            property: Book::PARAM_PAPER_TYPE,
            type: 'object'
        ),
        new OA\Property(
            items: new OA\Items(
                properties: [
                    new OA\Property(example: '1', property: 'id', type: 'integer'),
                ],
                type: 'object'
            ),
            property: Book::PARAM_AUTHORS,
            type: 'array'
        ),
        new OA\Property(
            properties: [
                new OA\Property(example: '1', property: 'id', type: 'integer'),
            ],
            property: Book::PARAM_PUBLISHING_HOUSE,
            type: 'object'
        ),
        new OA\Property(
            properties: [
                new OA\Property(example: '1', property: 'id', type: 'integer'),
            ],
            property: Book::PARAM_PUBLISHING_BRAND,
            type: 'object'
        ),
        new OA\Property(
            properties: [
                new OA\Property(example: '1', property: 'id', type: 'integer'),
            ],
            property: Book::PARAM_BOOK_SERIES,
            type: 'object'
        ),
        new OA\Property(
            properties: [
                new OA\Property(example: '2026-10-24', property: BookUserData::PARAM_RELEASE_DATE, type: 'string'),
                new OA\Property(example: 500, property: BookUserData::PARAM_LISTEN_PRICE_VALUE, type: 'integer'),
                new OA\Property(example: 'Ждём пока отзывы', property: BookUserData::PARAM_COMMENT, type: 'string'),
            ],
            property: Book::PARAM_BOOK_USER_DATA,
            type: 'object'
        ),
    ],
    schema: 'StoreBookRequest',
    type: 'object'
)]
class StoreBookRequestSchema {}
