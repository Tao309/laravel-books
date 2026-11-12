<?php

namespace App\OpenApi\Schemas\Cities;

use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(example: 'МОВ', property: 'code', type: 'string'),
        new OA\Property(example: 'Москва', property: 'title', type: 'string'),
    ],
    schema: 'UpdateCityRequest',
    type: 'object'
)]
class UpdateCityRequestSchema {}
