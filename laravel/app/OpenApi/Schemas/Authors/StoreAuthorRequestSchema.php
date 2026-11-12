<?php

namespace App\OpenApi\Schemas\Authors;

use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(example: 'Фёдор', property: 'first_name', type: 'string'),
        new OA\Property(example: 'Достоевский', property: 'last_name', type: 'string'),
    ],
    schema: 'StoreAuthorRequest',
    type: 'object'
)]
class StoreAuthorRequestSchema {}
