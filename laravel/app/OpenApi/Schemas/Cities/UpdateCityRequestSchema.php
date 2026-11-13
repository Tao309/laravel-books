<?php

namespace App\OpenApi\Schemas\Cities;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateCityRequest',
    properties: [
        new OA\Property(property: 'code', type: 'string', example: 'МОВ'),
        new OA\Property(property: 'title', type: 'string', example: 'Москва'),
    ],
    type: 'object'
)]
class UpdateCityRequestSchema {}
