<?php

namespace App\OpenApi\Schemas\BookSeries;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'BookSeriesResourceCollection',
    description: 'Collection of BookSeries',
    type: 'object',
    items: new OA\Items(
        ref: Refs::BOOK_SERIES_RESOURCE,
        type: 'object'
    )
)]
#[OA\Schema(
    schema: 'BookSeriesIndexResponse',
    description: 'Collection of BookSeries',
    allOf: [
        new OA\Schema(ref: Refs::RESPONSE),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    ref: Refs::PUBLISHING_BRANDS_RESOURCE_COLLECTION,
                    type: 'object'
                ),
            ],
            type: 'object'
        ),
    ]
)]
class BookSeriesResourceCollectionSchema {}
