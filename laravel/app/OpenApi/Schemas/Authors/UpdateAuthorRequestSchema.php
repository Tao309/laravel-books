<?php

namespace App\OpenApi\Schemas\Authors;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateAuthorRequest',
    properties: [
        new OA\Property(property: 'first_name', type: 'string', example: 'Фёдор'),
        new OA\Property(property: 'last_name', type: 'string', example: 'Достоевский'),
    ],
    type: 'object'
)]
class UpdateAuthorRequestSchema {}
