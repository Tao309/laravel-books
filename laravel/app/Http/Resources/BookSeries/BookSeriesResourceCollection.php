<?php

namespace App\Http\Resources\BookSeries;

use App\Http\Resources\AbstractResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'BookSeriesResourceCollection',
    description: 'Collection of BookSeries',
    type: 'object',
    items: new OA\Items(
        ref: '#/components/schemas/BookSeriesResource',
        type: 'object'
    )
)]
#[OA\Schema(
    schema: 'BookSeriesDataResourceCollection',
    description: 'Collection of BookSeries',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/BookSeriesResource',
                type: 'object'
            )
        ),
        new OA\Property(property: 'success', type: 'bool', example: true),
    ],
    type: 'object'
)]
class BookSeriesResourceCollection extends AbstractResourceCollection
{
    public $collects = BookSeriesResource::class;
}
