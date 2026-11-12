<?php

namespace App\OpenApi\Schemas\BookSeries;

use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(example: 'Звезды научной фантастики', property: 'name', type: 'string'),
    ],
    schema: 'StoreBookSeriesRequest',
    type: 'object'
)]
class StoreBookSeriesRequestSchema {}
