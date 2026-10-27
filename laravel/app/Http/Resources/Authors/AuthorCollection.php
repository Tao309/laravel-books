<?php

namespace App\Http\Resources\Authors;

use App\Http\Resources\CommonCollection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AuthorCollection',
    description: 'Collection of authors',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/AuthorResource',
                type: 'object'
            )
        ),
    ],
    type: 'object'
)]
class AuthorCollection extends CommonCollection {}
