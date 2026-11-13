<?php

namespace App\OpenApi\Schemas\PaperTypes;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PaperTypeResource',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: '1'),
        new OA\Property(property: 'label', type: 'string', example: 'Типографская'),
        new OA\Property(
            property: 'description',
            type: 'string',
            example: 'Бумага средней плотности, светло-серая, слегка шероховатая'
        ),
        new OA\Property(property: 'updated_at', type: 'string', example: '2026-09-10 10:10:19'),
        new OA\Property(property: 'created_at', type: 'string', example: '2026-09-10 10:10:19'),
    ],
    type: 'object'
)]
#[OA\Schema(
    ref: Refs::RESPONSE,
    schema: 'PaperTypeDestroyResponse',
    description: 'Delete PaperType response',
)]
#[OA\Schema(
    schema: 'PaperTypeShowResponse',
    allOf: [
        new OA\Schema(ref: Refs::RESPONSE),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    ref: Refs::PAPER_TYPE_RESOURCE,
                    type: 'object'
                ),
            ],
            type: 'object'
        ),
    ]
)]
class PaperTypeResourceSchema {}
