<?php

namespace App\OpenApi\Schemas\Users;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserResource',
    properties: [
        new OA\Property(property: User::PARAM_ID, type: 'integer', example: '1'),
        new OA\Property(property: User::PARAM_NAME, type: 'string', example: 'admin'),
        new OA\Property(property: User::PARAM_EMAIL, type: 'string', example: 'admin@mail.ru'),
        new OA\Property(property: User::PARAM_IS_ACTIVE, type: 'boolean', example: true),
        new OA\Property(property: Model::UPDATED_AT, type: 'string', example: '2026-09-10 10:10:19'),
        new OA\Property(property: Model::CREATED_AT, type: 'string', example: '2026-09-10 10:10:19'),
    ],
    type: 'object'
)]
class UserResourceSchema {}
