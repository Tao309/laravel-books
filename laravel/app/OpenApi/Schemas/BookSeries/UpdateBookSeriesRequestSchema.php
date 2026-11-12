<?php

namespace App\OpenApi\Schemas\BookSeries;

use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(example: 'Звезды научной фантастики', property: 'name', type: 'string'),
    ],
    schema: 'UpdateBookSeriesRequest',
    type: 'object'
)]
class UpdateBookSeriesRequestSchema {}
