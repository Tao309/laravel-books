<?php

namespace App\Http\Resources\BookSeries;

use App\Http\Resources\CommonResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'BookSeriesResourceCollection',
    description: 'Collection of Publishing Series',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/BookSeriesResource',
                type: 'object'
            )
        ),
    ],
    type: 'object'
)]
class BookSeriesResourceCollection extends CommonResourceCollection {}
