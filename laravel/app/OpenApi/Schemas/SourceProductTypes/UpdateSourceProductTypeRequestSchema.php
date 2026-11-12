<?php

namespace App\OpenApi\Schemas\SourceProductTypes;

use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(example: 'toys', property: 'code', type: 'string'),
        new OA\Property(example: 'Игрушки', property: 'name', type: 'string'),
    ],
    schema: 'UpdateSourceProductTypeRequest',
    type: 'object'
)]
class UpdateSourceProductTypeRequestSchema {}
