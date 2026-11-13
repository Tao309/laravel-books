<?php

namespace App\OpenApi\Schemas\SourceProductTypes;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreSourceProductTypeRequest',
    properties: [
        new OA\Property(property: 'code', type: 'string', example: 'toys'),
        new OA\Property(property: 'name', type: 'string', example: 'Игрушки'),
    ],
    type: 'object'
)]
class StoreSourceProductTypeRequestSchema {}
