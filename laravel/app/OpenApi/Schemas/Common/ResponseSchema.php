<?php

namespace App\OpenApi\Schemas\Common;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Response',
    description: 'Common response fields',
    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'Success response'),
        new OA\Property(property: 'success', type: 'boolean', example: true),
    ],
    type: 'object',
)]
class ResponseSchema {}
