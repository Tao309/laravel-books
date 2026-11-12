<?php

namespace App\OpenApi\Schemas\PaperTypes;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(example: '1', property: 'id', type: 'integer'),
        new OA\Property(example: 'Типографская', property: 'label', type: 'string'),
        new OA\Property(
            example: 'Бумага средней плотности, светло-серая, слегка шероховатая',
            property: 'description',
            type: 'string'
        ),
        new OA\Property(example: '2026-09-10 10:10:19', property: 'updated_at', type: 'string'),
        new OA\Property(example: '2026-09-10 10:10:19', property: 'created_at', type: 'string'),
    ],
    schema: 'PaperTypeResource',
    type: 'object'
)]
#[OA\Schema(
    properties: [
        new OA\Property(
            property: 'data',
            ref: Refs::PAPER_TYPE_RESOURCE,
            type: 'object'
        ),
        new OA\Property(example: true, property: 'success', type: 'bool'),
    ],
    schema: 'PaperTypeDataResource',
    type: 'object'
)]
class PaperTypeResourceSchema {}
