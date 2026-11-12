<?php

namespace App\OpenApi\Schemas\BookSeries;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    description: 'Collection of BookSeries',
    items: new OA\Items(
        ref: Refs::BOOK_SERIES_RESOURCE,
        type: 'object'
    ),
    schema: 'BookSeriesResourceCollection',
    type: 'object'
)]
#[OA\Schema(
    description: 'Collection of BookSeries',
    properties: [
        new OA\Property(
            items: new OA\Items(
                ref: Refs::BOOK_SERIES_RESOURCE,
                type: 'object'
            ),
            property: 'data',
            type: 'array'
        ),
        new OA\Property(example: true, property: 'success', type: 'bool'),
    ],
    schema: 'BookSeriesDataResourceCollection',
    type: 'object'
)]
class BookSeriesResourceCollectionSchema {}
