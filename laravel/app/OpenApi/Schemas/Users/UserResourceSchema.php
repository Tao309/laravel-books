<?php

namespace App\OpenApi\Schemas\Users;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserResource',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: '1'),
        new OA\Property(property: 'name', type: 'string', example: 'admin'),
        new OA\Property(property: 'email', type: 'string', example: 'admin@mail.ru'),
        new OA\Property(property: 'updated_at', type: 'string', example: '2026-09-10 10:10:19'),
        new OA\Property(property: 'created_at', type: 'string', example: '2026-09-10 10:10:19'),
    ],
    type: 'object'
)]
class UserResourceSchema {}
