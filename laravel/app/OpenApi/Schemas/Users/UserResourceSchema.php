<?php

namespace App\OpenApi\Schemas\Users;

use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(example: '1', property: 'id', type: 'integer'),
        new OA\Property(example: 'admin', property: 'name', type: 'string'),
        new OA\Property(example: 'admin@mail.ru', property: 'email', type: 'string'),
        new OA\Property(example: '2026-09-10 10:10:19', property: 'updated_at', type: 'string'),
        new OA\Property(example: '2026-09-10 10:10:19', property: 'created_at', type: 'string'),
    ],
    schema: 'UserResource',
    type: 'object'
)]
class UserResourceSchema {}
