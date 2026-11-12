<?php

namespace App\OpenApi\Schemas\BindingTypes;

use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(example: 'Мягкий переплёт', property: 'label', type: 'string'),
    ],
    schema: 'StoreBindingTypeRequest',
    type: 'object'
)]
class StoreBindingTypeRequestSchema {}
