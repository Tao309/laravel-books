<?php

namespace App\OpenApi\Schemas\BookSeries;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateBookSeriesRequest',
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'Звезды научной фантастики'),
    ],
    type: 'object'
)]
class UpdateBookSeriesRequestSchema {}
