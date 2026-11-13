<?php

namespace App\OpenApi\Schemas\BindingTypes;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreBindingTypeRequest',
    properties: [
        new OA\Property(property: 'label', type: 'string', example: 'Мягкий переплёт'),
    ],
    type: 'object'
)]
class StoreBindingTypeRequestSchema {}
